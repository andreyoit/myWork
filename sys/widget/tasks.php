<div class="portlet light tasks-widget">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-share font-green-haze hide"></i>
								<span class="caption-subject font-green-haze bold uppercase">Tasks</span>
								<span class="caption-helper">List</span>
							</div>
							<div class="actions">
								<a href="javascript:;" class="btn btn-circle red-sunglo btn-sm">
										<i class="fa fa-plus"></i> New </a>
								<div class="btn-group">
									
									<a class="btn green-haze btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									Options <i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="javascript:;">
											<i class="i"></i><i class="fa fa-check"></i> Complete Task </a>
										</li>
										<li class="divider">
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-plus"></i> New Task </a>
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-edit"></i> Modify Task </a>
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-trash-o"></i> Delete Task </a>
										</li>
										<li class="divider">
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-exclamation-circle"></i> Pending <span class="badge badge-danger">
											4 </span>
											</a>
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-check-circle"></i> Completed <span class="badge badge-success">
											12 </span>
											</a>
										</li>
										<li>
											<a href="javascript:;">
											<i class="fa fa-exclamation-triangle"></i> Overdue <span class="badge badge-warning">
											9 </span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div class="task-content">
								<div class="scroller" style="height: 305px;" data-always-visible="1" data-rail-visible1="1">
									<!-- START TASK LIST -->
									<ul class="task-list">
										<?php include 'https://mywork-andreaem-dev.c9.io/sys/db/tasks.get.dash.php'; ?>
										
									</ul>
									<!-- END START TASK LIST -->
								</div>
							</div>
							<div class="task-footer">
								<div class="btn-arrow-link pull-right">
									<a href="javascript:;">See All Records</a>
									<i class="icon-arrow-right"></i>
								</div>
							</div>
						</div>
					</div>