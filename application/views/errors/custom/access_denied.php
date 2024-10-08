<section class="content_section">
    <section class="breadcrumb_section">
        <div class="container-fluid">
            <div class="bg-light">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-7 col-xl-8">
                        <ol class="breadcrumb bg-light">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Access Denied</li>
                        </ol>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-5 col-xl-4"></div>
                </div>
            </div>
        </div>
    </section>
    <div class="re_menu">
        <div id="menu">
            <i class="fas fa-bars"></i>
        </div>
        <div id="nav_list_panel">
            <i id="re_menu_close" class="far fa-times-circle"></i>
            <ul class="navbar-nav nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#staff_designation">Access Denied</a>
                </li>
            </ul>
        </div>
    </div>
    <section class="tab_content_section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="tab_nav"> 
                        <div class="tab_box ">
                            <div class="tab-content">
                                <div class="tab-pane active">
                                    <h1><?php echo $heading; ?></h1>
                                    <?php echo $message; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>