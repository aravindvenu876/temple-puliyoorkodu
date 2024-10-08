<!DOCTYPE html>
<html lang="en">
<head>
    <title>Temple Management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php 
    //CSS Files
    add_css('assets/css/dataTables.css');
    add_css('assets/css/bootstrap.min.css');
    add_css('assets/css/okayNav.css');
    add_css('assets/css/bootstrap.css');
    add_css('assets/css/bootstrap-datepicker3.min.css');
    add_css('assets/css/jquery-confirm.min.css');
    add_css('assets/css/custom_version.css');
    add_css('assets/css/developer.css');
    add_css('assets/css/cal_style.css');
    add_css('assets/css/select2.min.css');
    add_css('assets/font-awesome/css/font-awesome.min.css');
    //JS Files
    add_js('assets/js/jquery.min.js');
    add_js('assets/js/jquery-1.11.3.js');
    add_js('assets/js/popper.min.js');
    add_js('assets/js/bootstrap.min.js');
    add_js('assets/js/jquery.dataTables.min.js');
    add_js('assets/js/parsley.js');
    add_js('assets/js/moment.js');
    add_js('assets/js/bootstrap-datepicker.js"');
    add_js('assets/js/jquery-confirm.min.js');
    add_js('assets/js/form.js');
    add_js('assets/js/toaster.js');
    add_js('assets/js/babel/babel.js');
    add_js('assets/js/utils.js');
    add_js('assets/js/bootbox.min.js');
    add_js('assets/js/script.js');
    add_js('assets/js/scrollBar.js');
    add_js('assets/js/jquery.okayNav.js');
    add_js('assets/js/jquery.validate.min.js');
    add_js('assets/js/scrollBar.js');
    add_js('assets/js/jquery.marquee.js');
    add_js('assets/js/Chart.min.js');
    add_js('assets/js/core.js');
    add_js('assets/js/charts.js');
    add_js('assets/js/animated.js');
    add_js('assets/js/select2.min.js');
    function add_css($url) {
        echo '<link href="'.base_url($url).'?v='.SCRIPT_CACHE_CODE.'" rel="stylesheet" type="text/css" />';
    }
    function add_js($url) {
        echo '<script src="'.base_url($url).'?v='.SCRIPT_CACHE_CODE.'"></script>';
    }
    ?>
</head>
<body>
    <div class="load">
        <img src="<?php echo base_url('assets/images/loading.svg') ?>">
    </div>
    <section class="header">
        <a href="<?php echo base_url() ?>" class="logo">
            <img src='<?php echo base_url("assets/images/logo.png");?>' class="img-fluid" >
        </a>
		<?php 
		$templeName = "";
		foreach($temples as $row){ 
            if($row->id == $this->session->userdata('temple'))
				$templeName = $row->temple;
        }
        echo '<h1>Temple Management System - '.$templeName.'</h1>';
        ?>
        <a href="<?php echo base_url() ?>logout"><span class="fa fa-power-off"></span></a>
        <div class="dropdown">
            <a class=" dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fa fa-cog"></span>
            </a>
            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                <li class="dropdown-item">
                    <a href="<?php echo base_url('auth/change_password') ?>">Change Password</a>
                </li>
                <li class="dropdown-submenu  left-submenu">
                    <a class="dropdown-item" tabindex="-1" href="javascript:void(0)">Switch Language</a>
                    <ul class="dropdown-menu">
                        <?php 
                        foreach($languages as $row){
                            if($row->id == $this->session->userdata('language'))
                                echo "<li class='dropdown-item' style='background-color:#589ffc;'>";
                            else
                                echo "<li class='dropdown-item'>";
                            echo "<a href='".base_url()."auth/switch_language/$row->id'>$row->language</a>";
                            echo "</li>";
                        } 
                        ?>
                    </ul>
                </li>
                <li class="dropdown-submenu  left-submenu">
                    <a class="dropdown-item" tabindex="-1" href="javascript:void(0)">Switch Temple</a>
                    <ul class="dropdown-menu">
                        <?php 
                        foreach($temples as $row){
                            if($row->id == $this->session->userdata('temple'))
                                echo "<li class='dropdown-item' style='background-color: red;'>";
                            else
                                echo "<li class='dropdown-item'>";
                            echo "<a href='".base_url()."auth/switch_temple/$row->id'>$row->temple</a>";
                            echo "</li>";
                        } 
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
		<div id="noti">
			<div id="noti_Counter">0</div> 
			<div id="noti_Button"><i class="fa fa-bell"></i></div> 
			<div id="notifications">
				<h3>Notifications</h3>
				<div class="Notlist">
                    <div class="sb-container customScrollbar">
                        <ul></ul>
                    </div>
				</div>
			</div>
		</div>
        <span class="user_name"><?php echo $this->session->userdata('name') ?></span>
    </section>
    <section class="menu_wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="row MenuBox">
                    <div role="" id="nav-main"  class="okayNav">
                        <ul>
							<?php 
                            foreach($mainmenu as $row){
                                echo "<li>";
                                if($main_menu_id == $row['id']){
                                    echo "<a class='active' href='".base_url().'dashboard/access_menu/'.$row['link']."'>".$row['menu']."</a>";
                                }else{
                                    echo "<a  href='".base_url().'dashboard/access_menu/'.$row['link']."'>".$row['menu']."</a>";
                                }
                                echo "</li>";
                            } 
                            ?> 
                      </ul>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
<section class="content_section">
   <section class="breadcrumb_section">
      <div class="container-fluid">
         <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="<?php echo base_url().$mainMenuLabel['menu_link'] ?>"><?php echo $mainMenuLabel['menu'] ?></a></li>
            <?php if($subMenuLabel['sub_menu_id'] != 0){ ?>
                <li class="breadcrumb-item"><b><a href="<?php echo base_url().$subMenuLabel['sub_menu_link'] ?>"><?php echo $subMenuLabel['sub_menu']; ?></a></b></li>
            <?php } ?>
         </ol>
      </div>
   </section>
   <section class="tab_content_section">
      <div class="container-fluid">
         <div class="row">
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-4 col-xs-12">
               <nav id="SideNav">
                  <ul>
                     <?php 
                        foreach($submenu as $row){
                          	echo "<li class='nav-item'>";
                          	if($sub_menu_id == $row['id']){
                            	echo "<a class='nav-link active_sub_menu' style='border-bottom: 1px solid #B14B10;' href='".base_url().$row['link']."'>".$row['sub_menu']."</a>";
                         	}else{
                            	echo "<a class='nav-link' href='".base_url().$row['link']."'>".$row['sub_menu']."</a>";
                         	}
                         	echo "</li>";
                        } 
                        ?>
                  </ul>
               </nav>
            </div>
            <script>
                function goTO(link){
                    window.location.href = link;
                }
            </script>
