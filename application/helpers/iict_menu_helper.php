<?php

function get_footer_items(){
    $CI =& get_instance();
    $menuAll = array();
    $menu = $CI->site_settings->getAllMenu();
    
    return $menu;
}

function get_menu_items() {
    $CI =& get_instance();
    if($CI->uri->segment(2) == 'Staff_portal' || $CI->uri->segment(2) == 'staff_portal') {
        $menu_staffPortal = Array
        (
                0 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'EDUCATION',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard edu',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                )
                ,1 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'ABOUT',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard about',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                )
                ,13 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'PHOTO',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard photo',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                2 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'POSITIONS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard employement',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                3 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'RESEARCH INTEREST',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard interest',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                4 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'RESEARCH GROUP',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard group',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                5 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'PROJECTS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard projects',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                6 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'PUBLICATIONS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard publications',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                7 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'AWARDS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard awards',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                8 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'PATENTS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard patents',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                9 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'LECTURES',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard lectures',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                10 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'VISITED COUNTRIES',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard countries',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                11 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'CONTRIBUTIONS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard contributions',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
                12 => Array
                (
                    'role_id' => $CI->session->userdata('role'),
                    'menu' =>  'SORTING STAFF DETAILS',
                    'url' =>  'staff_portal_profile',
                    'has_submenu' =>  0,
                    'icon' =>  'glyphicon glyphicon-dashboard sorting',
                    'sub_menu' => FALSE,
                    'view_status' => 1
                ),
            );
        return $menu_staffPortal;
    }
    
    $menuItems = array();
    $menu = $CI->site_settings->getMenuItems();
    foreach($menu as $item) {
        if($item['has_submenu'] == 1 && $item['type'] == 1) {
            $sub = isset($menuItems[$item['id']]['sub_menus']) ? $menuItems[$item['id']]['sub_menus'] : [];
            $menuItems[$item['id']] = $item;
            $menuItems[$item['id']]['sub_menu'] = TRUE;
            if($sub) {
                $menuItems[$item['id']]['sub_menus'] = $sub;
            }
        } else {
            if($item['type'] == 1) {
                $menuItems[$item['id']] = $item;
                $menuItems[$item['id']]['sub_menu'] = FALSE;
            }
        }
        if($item['type'] == 2) {
            $menuItems[$item['main_manu_id']]['sub_menus'][] = $item;
        }
    }
    // echo "<pre>";
    // print_r($menuItems);
    // die();
    $outputMenuArray = [];
    $outputMenuArray1 = [];
    // echo "<pre>";
    // print_r($menuItems);
    foreach($menuItems as $key => $item) {
        // echo $item['menu_order']."-".$item['id']."<br>";

        $outputMenuArray[$item['menu_order']] = $item;
    }
    ksort($outputMenuArray);
    foreach($outputMenuArray as $key  => $item){
        $subMenuArray = [];
      if($item['has_submenu']==1 && $item['sub_menu']==1){
        if(!isset($item['sub_menus'])) { continue; }
            foreach( $item['sub_menus'] as $submnu){
                $subMenuArray[$submnu['menu_order']]= $submnu;
            }
            ksort( $subMenuArray);
            $item['sub_menus']= Array();
            $item['sub_menus']= $subMenuArray;
           
        }
        $outputMenuArray1[$item['menu_order']]=$item;
    }
    return $outputMenuArray1;
}

?>