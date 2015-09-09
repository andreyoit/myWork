<!-- BEGIN REGIONAL STATS PORTLET-->
					<div class="portlet light ">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-share font-red-sunglo"></i>
								<span class="caption-subject font-red-sunglo bold uppercase">Regional Stats</span>
							</div>
							<div class="actions">
								<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
								<i class="icon-cloud-upload"></i>
								</a>
								<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
								<i class="icon-wrench"></i>
								</a>
								<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;">
								</a>
								<a class="btn btn-circle btn-icon-only btn-default" href="javascript:;">
								<i class="icon-trash"></i>
								</a>
							</div>
						</div>
						<div class="portlet-body">
							<div id="region_statistics_loading">
								<img src="../../assets/admin/layout/img/loading.gif" alt="loading"/>
							</div>
							<div id="region_statistics_content" class="display-none">
								<div class="btn-toolbar margin-bottom-10">
									<div class="btn-group btn-group-circle" data-toggle="buttons">
										<a href="index.html" class="btn grey-salsa btn-sm active">
										Users </a>
										<a href="index.html" class="btn grey-salsa btn-sm">
										Orders </a>
									</div>
									<div class="btn-group pull-right">
										<a href="index.html" class="btn btn-circle grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
										Select Region <span class="fa fa-angle-down">
										</span>
										</a>
										<ul class="dropdown-menu pull-right">
											<li>
												<a href="javascript:;" id="regional_stat_world">
												World </a>
											</li>
											<li>
												<a href="javascript:;" id="regional_stat_usa">
												USA </a>
											</li>
											<li>
												<a href="javascript:;" id="regional_stat_europe">
												Europe </a>
											</li>
											<li>
												<a href="javascript:;" id="regional_stat_russia">
												Russia </a>
											</li>
											<li>
												<a href="javascript:;" id="regional_stat_germany">
												Germany </a>
											</li>
										</ul>
									</div>
								</div>
								<div id="vmap_world" class="vmaps display-none">
								</div>
								<div id="vmap_usa" class="vmaps display-none">
								</div>
								<div id="vmap_europe" class="vmaps display-none">
								</div>
								<div id="vmap_russia" class="vmaps display-none">
								</div>
								<div id="vmap_germany" class="vmaps display-none">
								</div>
							</div>
						</div>
					</div>
					<!-- END REGIONAL STATS PORTLET-->