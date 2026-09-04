{{-- รวมไฟล์นี้ไว้ "ครั้งเดียว" ในหน้า dashboard.dashboard (หรือ layout กลาง) --}}
<div id="courseListModal" class="course-list-modal-overlay" onclick="if (event.target === this) closeCourseListModal();">
    <div class="course-list-modal">
        <button type="button" class="course-list-modal__close" onclick="closeCourseListModal()" aria-label="ปิด">&times;</button>
        <div id="courseListModalBody">
            <div class="course-list-loading">กำลังโหลด...</div>
        </div>
    </div>
</div>

<style>
.course-list-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .5);
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.course-list-modal-overlay.is-open {
    display: flex;
}

.course-list-modal {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    width: 92%;
    max-width: 560px;
    max-height: 82vh;
    overflow-y: auto;
    position: relative;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .2);
}

.course-list-modal__close {
    position: absolute;
    top: 12px;
    right: 16px;
    border: none;
    background: none;
    font-size: 26px;
    line-height: 1;
    cursor: pointer;
    color: #888;
}

.course-list-modal__close:hover {
    color: #333;
}

.course-list-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-right: 24px;
}

.course-list-modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.course-list-modal-count {
    font-size: 13px;
    color: #888;
    white-space: nowrap;
}

.course-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.course-list__item {
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.course-list__item:last-child {
    border-bottom: none;
}

.course-list__title {
    font-weight: 500;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.course-list__meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #888;
}

.course-list__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 4px;
}

.course-list__action {
    flex-shrink: 0;
    padding: 4px 12px;
    border: 1px solid #1d71b8;
    color: #1d71b8;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
    white-space: nowrap;
}

.course-list__action:hover {
    background: #f4f9fd;
}

.course-list-empty,
.course-list-loading {
    text-align: center;
    color: #888;
    padding: 32px 0;
}

.course-list-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #eee;
}

.course-list-pagination__btn {
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 6px;
    padding: 6px 14px;
    cursor: pointer;
    font-size: 13px;
}

.course-list-pagination__btn:disabled {
    opacity: .4;
    cursor: not-allowed;
}

.course-list-pagination__info {
    font-size: 13px;
    color: #666;
}
</style>

<script>
function openCourseListModal(status) {
    document.getElementById('courseListModal').classList.add('is-open');
    loadCourseList(status, 1);
}

function closeCourseListModal() {
    document.getElementById('courseListModal').classList.remove('is-open');
}

function loadCourseList(status, page) {
    const body = document.getElementById('courseListModalBody');
    body.dataset.status = status;
    body.innerHTML = '<div class="course-list-loading">กำลังโหลด...</div>';

    fetch(`{{ route('dashboard.course-list.ajax') }}?status=${encodeURIComponent(status)}&page=${page}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(res => res.text())
        .then(html => {
            body.innerHTML = html;
        })
        .catch(() => {
            body.innerHTML = '<div class="course-list-empty">โหลดข้อมูลไม่สำเร็จ กรุณาลองใหม่</div>';
        });
}

document.addEventListener('click', function (e) {
    const btn = e.target.closest('#courseListModalBody .course-list-pagination__btn');

    if (btn && !btn.disabled) {
        const status = document.getElementById('courseListModalBody').dataset.status;
        loadCourseList(status, btn.dataset.page);
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeCourseListModal();
    }
});
</script>
