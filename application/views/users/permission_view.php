
<div class="col-md-12 col-sm-12 col-12 ">
    <input type="hidden" name="role_id" id="role_id" value="<?php echo $role['id'] ?>"/>
    <h3 id="form_title_h2">Define User Privileges for <?php echo $role['role'] ?></h3>
	<hr class="hrCustom">
</div>
<div class="col-md-12 col-sm-12 col-12 ">
<div class="table-responsive">
    <table class="table table-bordered scrolling table-sm Tblcategory table-striped">
        <tbody>
        <!-- <thead> -->
            <tr class="bg-warning text-white">
                <th>Sl#</th>
                <th>Menu</th>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="main_view" id="main_view" onclick="check_all_view()"/>
                        <label class="custom-control-label white" for="main_view">View</label>
                    </div>
                </th><th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" name="main_modify" id="main_modify" onclick="check_all_modify()"/>
                        <label class="custom-control-label white" for="main_modify">Modify</label>
                    </div>
                </th>
            </tr>
        <!-- </thead> -->
            <?php 
            $i = 0;
            foreach($menu as $row){
                $i++;
                echo "<tr class='TblMinCategory'>";
                echo "<td class='TblSubCategoryLabel'>".$i;
                echo "<input type='hidden' name='main_menu[]' value='0'/>";
                echo "<input type='hidden' name='menu[]' value='$row->id'/>";
                echo "<input type='hidden' name='type[]' value='main'/></td>";
                echo "<td class='TblSubCategoryLabel'>$row->menu</td>";
                $permission = $this->common_functions->check_role_permission($role['id'],$row->id,'main');
                echo "<td>";
                echo '<div class="custom-control custom-checkbox">';
                if(empty($permission)){
                    echo "<input type='checkbox' name='view_main_$row->id' class='custom-control-input main_menu_view' id='main_menu_view_$row->id' onclick='main_menu_view($row->id)' checked/>";
                }else{
                    if($permission['view_status'] == 1){
                        echo "<input type='checkbox' name='view_main_$row->id' class='custom-control-input main_menu_view' id='main_menu_view_$row->id' onclick='main_menu_view($row->id)' checked/>";
                    }else{
                        echo "<input type='checkbox' name='view_main_$row->id' class='custom-control-input main_menu_view' id='main_menu_view_$row->id' onclick='main_menu_view($row->id)'/>";
                    }
                }
                echo '<label class="custom-control-label" for="main_menu_view_'.$row->id.'">View</label>';
                echo "</div>";
                echo "</td><td>";
                echo '<div class="custom-control custom-checkbox">';
                if(empty($permission)){
                    echo "<input type='checkbox' name='modify_main_$row->id' class='custom-control-input main_menu_modify' id='main_menu_modify_$row->id' onclick='main_menu_modify($row->id)' checked/>";
                }else{
                    if($permission['modify_status'] == 1){
                        echo "<input type='checkbox' name='modify_main_$row->id' class='custom-control-input main_menu_modify' id='main_menu_modify_$row->id' onclick='main_menu_modify($row->id)' checked/>";
                    }else{
                        echo "<input type='checkbox' name='modify_main_$row->id' class='custom-control-input main_menu_modify' id='main_menu_modify_$row->id' onclick='main_menu_modify($row->id)'/>";
                    }
                }
                echo '<label class="custom-control-label" for="main_menu_modify_'.$row->id.'">Modify</label>';
                echo "</div>";
                echo "</td>";
                echo "</tr>";
                $j = 0;
                foreach($sub_menu as $val){
                    if($row->id == $val->menu_id){
                        $j++;
                        echo "<tr class='TblSubCategory'>";
                        echo "<td class='TblSubCategoryLabel'>".$i.".".$j;
                        echo "<input type='hidden' name='main_menu[]' value='$row->id'/>";
                        echo "<input type='hidden' name='menu[]' value='$val->id'/>";
                        echo "<input type='hidden' name='type[]' value='sub'/></td>";
                        echo "<td class='TblSubCategoryLabel'>$val->sub_menu</td>";
                        $permission = $this->common_functions->check_role_permission($role['id'],$val->id,'sub');
                        echo "<td>";
                        echo '<div class="custom-control custom-checkbox MargLeft25">';
                        if(empty($permission)){
                            echo "<input type='checkbox' name='view_sub_$val->id' class='custom-control-input sub_menu_view sub_menu_view_$row->id' id='sub_menu_view_$val->id' checked/>";
                        }else{
                            if($permission['view_status'] == 1){
                                echo "<input type='checkbox' name='view_sub_$val->id' class='custom-control-input sub_menu_view sub_menu_view_$row->id' id='sub_menu_view_$val->id' checked/>";
                            }else{
                                echo "<input type='checkbox' name='view_sub_$val->id' class='custom-control-input sub_menu_view sub_menu_view_$row->id' id='sub_menu_view_$val->id'/>";
                            }
                        }
                        echo '<label class="custom-control-label" for="sub_menu_view_'.$val->id.'">View</label>';
                        echo "</div>";
                        echo "</td><td>";
                        echo '<div class="custom-control custom-checkbox MargLeft25">';
                        if(empty($permission)){
                            echo "<input type='checkbox' name='modify_sub_$val->id' class='custom-control-input sub_menu_modify sub_menu_modify_$row->id' id='sub_menu_modify_$val->id' checked/>";
                        }else{
                            if($permission['modify_status'] == 1){
                                echo "<input type='checkbox' name='modify_sub_$val->id' class='custom-control-input sub_menu_modify sub_menu_modify_$row->id' id='sub_menu_modify_$val->id' checked/>";
                            }else{
                                echo "<input type='checkbox' name='modify_sub_$val->id' class='custom-control-input sub_menu_modify sub_menu_modify_$row->id' id='sub_menu_modify_$val->id'/>";
                            }
                        }
                        echo '<label class="custom-control-label" for="sub_menu_modify_'.$val->id.'">Modify</label>';
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            } 
            ?>
        </tbody>
    </table>
	</div>
</div>
<script>
    function check_all_view(){
        if($("#main_view:checkbox:checked").length == 0){
            $('.main_menu_view').prop('checked', false);
            $('.sub_menu_view').prop('checked', false);
        }else{
            $('.main_menu_view').prop('checked', true);
            $('.sub_menu_view').prop('checked', true);
        }
    }
    function check_all_modify(){
        if($("#main_modify:checkbox:checked").length == 0){
            $('.main_menu_modify').prop('checked', false);
            $('.sub_menu_modify').prop('checked', false);
        }else{
            $('.main_menu_modify').prop('checked', true);
            $('.sub_menu_modify').prop('checked', true);
        }
    }
    function main_menu_view(val){
        if($("#main_menu_view_"+val+":checkbox:checked").length == 0){
            $('.sub_menu_view_'+val).prop('checked', false);
        }else{
            $('.sub_menu_view_'+val).prop('checked', true);
        }
    }
    function main_menu_modify(val){
        if($("#main_menu_modify_"+val+":checkbox:checked").length == 0){
            $('.sub_menu_modify_'+val).prop('checked', false);
        }else{
            $('.sub_menu_modify_'+val).prop('checked', true);
        }
    }
</script>