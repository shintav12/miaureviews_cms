<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{url('/')}}">Inicio</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <?php if($page_subtitle == "") {?>
            <span>{{$page_title}}</span>
            <?php }else{?>
                <a href="{{route($menu_active)}}">{{$page_title}}</a>
                <i class="fa fa-circle"></i>
            <?php }?>
        </li>
        <?php if($page_subtitle != ""){?>
        <li>
            <span>{{$page_subtitle}}</span>
        </li>
        <?php }?>
    </ul>
</div>
