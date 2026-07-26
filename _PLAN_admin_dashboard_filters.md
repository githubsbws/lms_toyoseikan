> **ไฟล์ชั่วคราว — ลบทิ้งหลังทำเสร็จ**
> ไฟล์นี้ใช้สำหรับวางแผนก่อนเขียนโค้ดจริงเท่านั้น ไม่ใช่ spec ถาวรของโปรเจกต์
> เมื่อ implement ครบตามแผนแล้ว ให้ลบไฟล์นี้ทิ้ง

# แผน: ทำแถบค้นหา/filter ให้ AdminDashboardService

## ปัญหาที่ต้องแก้
1. `$filters` (department_id, section_id, line_id, team_id, date_from, date_to, search) ถูกส่งเข้า service แล้ว แต่ยังไม่มี method ไหน apply เงื่อนไขจริง
2. เงื่อนไข filter เดียวกัน (แผนก/ส่วนงาน/ไลน์/ทีม) จะถูกใช้ซ้ำในหลาย method → ถ้า copy-paste if ซ้ำทุกที่ จะรกและแก้ไขยาก (ย้อนกลับไปปัญหาที่คุยกันตอนแรกเรื่อง "รก")
3. `getOverviewStats` ใช้ raw SQL (`DB::selectOne` + subquery) ซึ่ง apply filter แบบมีเงื่อนไข (`if` + `where`) ไม่ได้ตรงไปตรงมาเหมือน builder ปกติ

## แนวทาง

### 1. Filter helper กลาง 1 ตัว (private method ใน service)
สร้าง private method เดียวที่รับ query builder (ใช้ได้ทั้ง Eloquent builder และ `DB::table()` builder เพราะ interface `where()` เหมือนกัน) แล้ว apply เงื่อนไขตาม `$filters` ให้ ใช้ `when()` ของ Laravel เพื่อเลี่ยงเขียน `if` ซ้ำ:

```
applyOrgFilters($query, array $filters, string $columnPrefix = '')
```

- ถ้ามี `department_id` → where department_org_id
- ถ้ามี `section_id` → where ผ่าน orgchart parent_id (ดู pattern จาก ReportPotentialService)
- ถ้ามี `line_id` → where ผ่าน orgchart parent_id
- ถ้ามี `team_id` → where team_id
- ถ้ามี `date_from`/`date_to` → whereBetween บน column วันที่ (ต้องกำหนดว่าแต่ละ query ใช้ column วันที่ตัวไหน เพราะ overdueCourses ใช้ end_date, popularCourses อาจใช้ learn_date)
- ถ้ามี `search` → where เข้า course_title หรือ department title (แต่ละ query ต่างคอลัมน์กัน จึงอาจต้องรับ column name เป็น parameter)

**ประเด็นที่ต้องตัดสินใจก่อนเขียน:** column ที่ apply filter ไม่เหมือนกันทุก query (บางที่ join กับ users, บางที่ join กับ course_online, บางที่ join orgchart ตรง) จึงอาจต้องออกแบบ helper ให้รับ "map ชื่อคอลัมน์" เป็น parameter แทนที่จะ hardcode ชื่อคอลัมน์ไว้ใน helper เดียว

### 2. getOverviewStats: เปลี่ยนจาก raw SQL เป็น Query Builder ปกติ
raw SQL subquery เดียวไม่รองรับ `if` + `where` ได้ง่าย ต้อง trade-off:

- **แผน A (เดิม):** คง 1 round-trip ไว้ แต่ไม่ apply filter กับการ์ดสรุปเลย (ตัวเลข "ทั้งระบบ" เสมอ) — เหมาะถ้าการ์ดสรุปควรคงที่ไม่ขึ้นกับ filter
- **แผน B:** เปลี่ยนเป็น 4 statement builder ปกติ (`DB::table('course_online')->where(...)->count()` ฯลฯ) แต่ละ statement apply filter ผ่าน `when()` ได้ตรงไปตรงมา — แลกกับ 4 round-trip แทน 1

**ต้องตัดสินใจ:** การ์ดสรุป 4 ใบ ควร "ไหว" ตาม filter ที่เลือกไหม หรือคงที่เป็นภาพรวมทั้งระบบเสมอ (ของ dashboard ทั่วไปมักให้การ์ดสรุปไหวตาม filter ด้วย เพื่อความสอดคล้องกับข้อมูลด้านล่าง)

### 3. Business rule ที่ยังค้างอยู่ (ต้อง confirm ก่อนเขียนจริง เหมือนที่ comment ไว้ในโค้ดเดิม)
- Overdue courses: นับผู้เรียนจาก department ของ course หรือจาก roadmap ผู้เรียนแต่ละคน
- Department learning: สูตร completion rate / pass rate คิดยังไง
- Search (`search`) จะค้นหาอะไร: ชื่อคอร์ส? ชื่อแผนก? ชื่อผู้ใช้? (แต่ละการ์ดอาจ search field คนละแบบ)

## ลำดับที่จะทำ (เมื่อ confirm แนวทางแล้ว)
1. เขียน `applyOrgFilters()` helper กลาง
2. ปรับ `getOverdueCourses`, `getDepartmentLearning`, `getPopularCourses` ให้เรียก helper นี้
3. ตัดสินใจเรื่อง `getOverviewStats` (แผน A หรือ B) แล้วแก้ตาม
4. ต่อ input จากแถบค้นหาใน blade เข้ากับ `$filters` ที่ AdminController เตรียมไว้แล้ว (ตอนนี้ query แต่ยังไม่ใช้)
5. ทดสอบว่าเลือก filter แต่ละตัวแล้วผลลัพธ์ถูกต้อง
6. **ลบไฟล์ `_PLAN_admin_dashboard_filters.md` นี้ทิ้ง**
