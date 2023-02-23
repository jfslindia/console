<!doctype html>
<html class="fixed has-top-menu">
<head>

	<!-- Basic -->
	<meta charset="UTF-8">

	<title>JFSL ADMIN</title>
	<meta name="keywords" content="JFSL ADMIN"/>
	<meta name="description" content="JFSL ADMIN">
	<meta name="author" content="JFSL ADMIN">

	<!-- Mobile Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

	<!-- Web Fonts  -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light"
		  rel="stylesheet" type="text/css">

	<!-- Vendor CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/animate/animate.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/font-awesome/css/all.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/magnific-popup/magnific-popup.css"/>
	<link rel="stylesheet"
		  href="<?php echo base_url(); ?>assets/newui/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css"/>

	<!-- Specific Page Vendor CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/jquery-ui/jquery-ui.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/jquery-ui/jquery-ui.theme.css"/>
	<link rel="stylesheet"
		  href="<?php echo base_url(); ?>assets/newui/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/vendor/morris/morris.css"/>

	<!-- Theme CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/css/theme.css"/>

	<!-- Skin CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/css/skins/default.css"/>

	<!-- Theme Custom CSS -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/newui/css/custom.css">

	<!-- Theme Datatable CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

	<!-- Head Libs -->
	<script src="<?php echo base_url(); ?>assets/newui/vendor/modernizr/modernizr.js"></script>
	<script> var base_url = "<?php echo base_url(); ?>"; </script>

	<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

	<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>

	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/jquery.blockUI.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/core/uikit.min.css"/>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/core/uikit.min.js"></script>


</head>
<body>
<section class="body">
	<?php
	$pages_array = unserialize(PAGES);
	?>
	<!-- start: header -->
	<header class="header header-nav-menu header-nav-top-line">
		<div class="logo-container">
			<a href="<?php echo base_url(); ?>console/home" class="logo">
				<img src="<?php echo base_url(); ?>assets/newui/img/logo.png" width="75" height="35" alt="Porto Admin"/>
			</a>
			<button class="btn header-btn-collapse-nav d-lg-none" data-toggle="collapse" data-target=".header-nav">
				<i class="fas fa-bars"></i>
			</button>

			<!-- start: header nav menu -->
			<div class="header-nav collapse">
				<div class="header-nav-main header-nav-main-effect-1 header-nav-main-sub-effect-1 header-nav-main-square">
					<nav>
						<ul class="nav nav-pills" id="mainNav">
							<li class="">
								<a class="nav-link" href="<?php echo base_url(); ?>console/home">
									Dashboard
								</a>
							</li>
							<li class="dropdown">
								<a class="nav-link dropdown-toggle" href="#">
									Menus
								</a>
								<ul class="dropdown-menu">
									<?php
									if (ADMIN_ACCESSIBLE_PAGES_STRING || ADMIN_PREVILIGE == 'root') {
										?>
									<?php } ?>
									<?php if (ADMIN_PREVILIGE == 'root') {
										foreach ($pages_array as $page) {
											if ($page['SUB_MENU'] == TRUE) {

												?>
												<li class="dropdown-submenu">
													<a class="nav-link">
														<?php echo $page['NAME']; ?>
													</a>
													<ul class="dropdown-menu">
														<?php for ($i = 0; $i < sizeof($page['SUB_MENU_ARRAY']); $i++) { ?>
														<li>
															<a class="nav-link" href="<?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
																<?php echo $page['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
															</a>
														</li>
														<?PHP } ?>
													</ul>
												</li>


											<?php } else { ?>

												<li >
													<a class="nav-link" href="<?php echo $page['LINK']; ?>">
														<?php echo $page['NAME']; ?>
													</a>
												</li>
												<?php
											}

										}
										?>

										<?php
									}
									else {

										if (ADMIN_ACCESSIBLE_PAGES_STRING) {

											foreach (unserialize(ADMIN_ACCESSIBLE_PAGES) as $accessible_page) {

												if ($pages_array[$accessible_page]['SUB_MENU'] == TRUE) {
													?>

													<li class="dropdown-submenu">
														<a class="nav-link">
															<?php echo $pages_array[$accessible_page]['NAME']; ?>
														</a>
														<ul class="dropdown-menu">
															<?php for ($i = 0; $i < sizeof($pages_array[$accessible_page]['SUB_MENU_ARRAY']); $i++) { ?>
																<li>
																	<a class="nav-link" href="<?php echo$pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU_LINK']; ?>">
																		<?php echo $pages_array[$accessible_page]['SUB_MENU_ARRAY'][$i]['SUB_MENU']; ?>
																	</a>
																</li>
															<?PHP } ?>
														</ul>
													</li>


													<?php
												} else {
													?>
													<li >
														<a class="nav-link" href="<?php echo $pages_array[$accessible_page]['LINK']; ?>">
															<?php echo $pages_array[$accessible_page]['NAME']; ?>
														</a>
													</li>
													<?php
												}

											}
										}
									} ?>
								</ul>

							</li>

						</ul>
					</nav>
				</div>
			</div>
			<!-- end: header nav menu -->
		</div>

		<!-- start: search & user box -->
		<div class="header-right">

			<a class="btn search-toggle d-none d-md-inline-block d-xl-none" data-toggle-class="active"
			   data-target=".search"><i class="fas fa-search"></i></a>


			<span class="separator"></span>

			<ul class="notifications">
				<li>
					<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fas fa-tasks"></i>
						<span class="badge">3</span>
					</a>

					<div class="dropdown-menu notification-menu large">
						<div class="notification-title">
							<span class="float-right badge badge-default">3</span>
							Tasks
						</div>

						<div class="content">
							<ul>
								<li>
									<p class="clearfix mb-1">
										<span class="message float-left">Generating Sales Report</span>
										<span class="message float-right text-dark">60%</span>
									</p>
									<div class="progress progress-xs light">
										<div class="progress-bar" role="progressbar" aria-valuenow="60"
											 aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
									</div>
								</li>

								<li>
									<p class="clearfix mb-1">
										<span class="message float-left">Importing Contacts</span>
										<span class="message float-right text-dark">98%</span>
									</p>
									<div class="progress progress-xs light">
										<div class="progress-bar" role="progressbar" aria-valuenow="98"
											 aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
									</div>
								</li>

								<li>
									<p class="clearfix mb-1">
										<span class="message float-left">Uploading something big</span>
										<span class="message float-right text-dark">33%</span>
									</p>
									<div class="progress progress-xs light mb-1">
										<div class="progress-bar" role="progressbar" aria-valuenow="33"
											 aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</li>
				<li>
					<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fas fa-envelope"></i>
						<span class="badge">4</span>
					</a>

					<div class="dropdown-menu notification-menu">
						<div class="notification-title">
							<span class="float-right badge badge-default">230</span>
							Messages
						</div>

						<div class="content">
							<ul>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="<?php echo base_url(); ?>assets/newui/img/!sample-user.jpg"
												 alt="Joseph Doe Junior" class="rounded-circle"/>
										</figure>
										<span class="title">Joseph Doe</span>
										<span class="message">Lorem ipsum dolor sit.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="<?php echo base_url(); ?>assets/newui/img/!sample-user.jpg"
												 alt="Joseph Junior" class="rounded-circle"/>
										</figure>
										<span class="title">Joseph Junior</span>
										<span class="message truncate">Truncated message. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam, nec venenatis risus. Vestibulum blandit faucibus est et malesuada. Sed interdum cursus dui nec venenatis. Pellentesque non nisi lobortis, rutrum eros ut, convallis nisi. Sed tellus turpis, dignissim sit amet tristique quis, pretium id est. Sed aliquam diam diam, sit amet faucibus tellus ultricies eu. Aliquam lacinia nibh a metus bibendum, eu commodo eros commodo. Sed commodo molestie elit, a molestie lacus porttitor id. Donec facilisis varius sapien, ac fringilla velit porttitor et. Nam tincidunt gravida dui, sed pharetra odio pharetra nec. Duis consectetur venenatis pharetra. Vestibulum egestas nisi quis elementum elementum.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="<?php echo base_url(); ?>assets/newui/img/!sample-user.jpg"
												 alt="Joe Junior" class="rounded-circle"/>
										</figure>
										<span class="title">Joe Junior</span>
										<span class="message">Lorem ipsum dolor sit.</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<figure class="image">
											<img src="<?php echo base_url(); ?>assets/newui/img/!sample-user.jpg"
												 alt="Joseph Junior" class="rounded-circle"/>
										</figure>
										<span class="title">Joseph Junior</span>
										<span class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam.</span>
									</a>
								</li>
							</ul>

							<hr/>

							<div class="text-right">
								<a href="#" class="view-more">View All</a>
							</div>
						</div>
					</div>
				</li>
				<li>
					<a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
						<i class="fas fa-bell"></i>
						<span class="badge">3</span>
					</a>

					<div class="dropdown-menu notification-menu">
						<div class="notification-title">
							<span class="float-right badge badge-default">3</span>
							Alerts
						</div>

						<div class="content">
							<ul>
								<li>
									<a href="#" class="clearfix">
										<div class="image">
											<i class="fas fa-thumbs-down bg-danger"></i>
										</div>
										<span class="title">Server is Down!</span>
										<span class="message">Just now</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<div class="image">
											<i class="fas fa-lock bg-warning"></i>
										</div>
										<span class="title">User Locked</span>
										<span class="message">15 minutes ago</span>
									</a>
								</li>
								<li>
									<a href="#" class="clearfix">
										<div class="image">
											<i class="fas fa-signal bg-success"></i>
										</div>
										<span class="title">Connection Restaured</span>
										<span class="message">10/10/2017</span>
									</a>
								</li>
							</ul>

							<hr/>

							<div class="text-right">
								<a href="#" class="view-more">View All</a>
							</div>
						</div>
					</div>
				</li>
			</ul>

			<span class="separator"></span>

			<div id="userbox" class="userbox">
				<a href="#" data-toggle="dropdown">
					<figure class="profile-picture">
						<img src="<?php echo base_url(); ?>assets/newui/img/!logged-user.jpg" alt="Joseph Doe"
							 class="rounded-circle" data-lock-picture="img/!logged-user.jpg"/>
					</figure>
					<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
						<span class="name"><?=$_SESSION['username']?></span>
						<span class="role">administrator</span>
					</div>

					<i class="fa custom-caret"></i>
				</a>

				<div class="dropdown-menu">
					<ul class="list-unstyled">
						<li class="divider"></li>
						<li>
							<a role="menuitem" tabindex="-1" href="#"><i class="fas fa-user"></i> My Profile</a>
						</li>
						<li>
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>console/change_password"
							   data-lock-screen="true"><i class="fas fa-lock"></i> Change Password</a>
						</li>
						<li>
							<a role="menuitem" tabindex="-1" href="<?php echo base_url(); ?>console/logout"><i
										class="fas fa-power-off"></i> Logout</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- end: search & user box -->
	</header>
	<!-- end: header -->


