                                            <table class="table table-bordered" style="font-size: smaller;">
												<thead>
													<tr class="active">
														<th>ลำดับ</th>
														<th>ชื่อ-นามสกุล</th>
														<th>ต่ำแหน่ง</th>
														<th>ความคืมหน้า</th>
														<th>หลักสูตรที่กำลังเรียน</th>
														<th>วันที่เสร็จ</th>
														<th>สถานะ</th>
														<th>วันปิดหลักสูตร</th>
													</tr>
												</thead>
												<tbody>

												@forelse($teamLearning as $item)

												<tr>

													<td>
														{{ $loop->iteration + ($teamLearning->currentPage()-1)*$teamLearning->perPage() }}
													</td>

													<td>

														<div style="display:flex;align-items:center;">

															<img
																src="https://static.vecteezy.com/system/resources/thumbnails/004/607/791/small_2x/man-face-emotive-icon-smiling-male-character-in-blue-shirt-flat-illustration-isolated-on-white-happy-human-psychological-portrait-positive-emotions-user-avatar-for-app-web-design-vector.jpg"
																class="img-responsive img-circle"
																style="max-height:18px;margin-right:5px;">

															{{ $item['fullname'] }}

														</div>

													</td>

													<td>

														{{ $item['position'] }}

													</td>

													<td width="180">

														<div style="display:flex;align-items:center;gap:5px;">

														@php

															$progressColor = 'progress-bar-danger';

															if ($item['progress'] >= 100) {

																$progressColor = 'progress-bar-success';

															} elseif ($item['progress'] >= 70) {

																$progressColor = 'progress-bar-warning';

															}

														@endphp
														
															<div class="progress"
																data-toggle="tooltip"
																data-placement="top"
																title="เรียนแล้ว {{ $item['pass_lesson'] }} / {{ $item['total_lesson'] }} บทเรียน"
																style="height:10px;width:80%;margin:0;">

																<div class="progress-bar {{ $progressColor }}"

																	role="progressbar"

																	style="width: {{ $item['progress'] }}%;transition: width .4s ease;"

																	aria-valuenow="{{ $item['progress'] }}"

																	aria-valuemin="0"

																	aria-valuemax="100">

																</div>

															</div>

															<span>

																{{ $item['progress'] }}%

															</span>

														</div>

													</td>

													<td>

														{{ $item['course_name'] }}

													</td>

													<td>

														@if($item['display_date'])

															{{ \Carbon\Carbon::parse($item['display_date'])->locale('th')->translatedFormat('d M') }}

															{{ \Carbon\Carbon::parse($item['display_date'])->year+543 }}

														@else

															-

														@endif

													</td>

													<td>

														@if($item['progress']==100)

															<span
																style="
																padding-inline:6px;
																padding-block:2px;
																color:var(--success-color);
																background-color:var(--success-color-transparent);
																border-radius:5px;">

																เรียนครบ

															</span>

														@else

															<span
																style="
																padding-inline:6px;
																padding-block:2px;
																color:var(--warning-color);
																background-color:var(--warning-color-transparent);
																border-radius:5px;">

																กำลังเรียน

															</span>

														@endif

													</td>

													<td>

														@if($item['display_date'])

															@php

																$date = \Carbon\Carbon::parse($item['display_date']);

																$days = now()->diffInDays($date, false);

																if($days < 0){

																	$badgeColor = "#343a40";
																	$text = "หมดอายุแล้ว";

																}elseif($days <= 7){

																	$badgeColor = "#dc3545";
																	$text = "เหลือ {$days} วัน";

																}elseif($days <= 30){

																	$badgeColor = "#ffc107";
																	$text = "เหลือ {$days} วัน";

																}else{

																	$badgeColor = "#28a745";
																	$text = "เหลือ {$days} วัน";

																}

															@endphp

															@if($item['progress'] != 100)

																<span
																	style="
																		background:{{ $badgeColor }};
																		color:white;
																		border-radius:20px;
																		padding:3px 10px;
																		font-size:11px;
																		display:inline-block;
																		margin-bottom:3px;
																	">

																	{{ $text }}

																</span>

																<br>

															@endif

														@else

															-

														@endif

													</td>

												</tr>

												@empty

												<tr>

													<td colspan="8" class="text-center">

														ไม่มีข้อมูล

													</td>

												</tr>

												@endforelse

												</tbody>
											</table>
												<div class="row" style="margin-top:15px;">

													<div class="col-md-8 col-sm-6" style="text-align:right;">

														<small class="text-muted">

															แสดง

															{{ $teamLearning->firstItem() ?? 0 }}

															-

															{{ $teamLearning->lastItem() ?? 0 }}

															จากทั้งหมด

															{{ $teamLearning->total() }}

															รายการ

														</small>

													</div>

													<div class="col-md-8 col-sm-6" style="text-align:right;">

														@if($teamLearning->hasPages())

															<ul class="pagination pagination-sm" style="margin:0; display:inline-flex;">

																{{-- Previous --}}
																@if($teamLearning->onFirstPage())

																	<li class="disabled">
																		<span>‹ ก่อนหน้า</span>
																	</li>

																@else

																	<li>
																		<a href="{{ $teamLearning->previousPageUrl() }}">
																			‹ ก่อนหน้า
																		</a>
																	</li>

																@endif

																{{-- Page --}}
																@php
																	$start = max($teamLearning->currentPage() - 2, 1);
																	$end = min($teamLearning->currentPage() + 2, $teamLearning->lastPage());
																@endphp

																@if($start > 1)
																	<li><a href="{{ $teamLearning->url(1) }}">1</a></li>

																	@if($start > 2)
																		<li class="disabled"><span>...</span></li>
																	@endif
																@endif

																@for($page = $start; $page <= $end; $page++)

																	@if($page == $teamLearning->currentPage())

																		<li class="active">
																			<span>{{ $page }}</span>
																		</li>

																	@else

																		<li>
																			<a href="{{ $teamLearning->url($page) }}">
																				{{ $page }}
																			</a>
																		</li>

																	@endif

																@endfor

																@if($end < $teamLearning->lastPage())

																	@if($end < $teamLearning->lastPage()-1)
																		<li class="disabled"><span>...</span></li>
																	@endif

																	<li>
																		<a href="{{ $teamLearning->url($teamLearning->lastPage()) }}">
																			{{ $teamLearning->lastPage() }}
																		</a>
																	</li>

																@endif

																{{-- Next --}}
																@if($teamLearning->hasMorePages())

																	<li>
																		<a href="{{ $teamLearning->nextPageUrl() }}">
																			ถัดไป ›
																		</a>
																	</li>

																@else

																	<li class="disabled">
																		<span>ถัดไป ›</span>
																	</li>

																@endif

															</ul>

														@endif

													</div>

												</div>