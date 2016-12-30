<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(!empty(Auth::user()->image))
                    {{ Html::image('profilepics/'.Auth::user()->image, 'User Image', array('class' => 'img-circle')) }}
                @else
                    <i class="img-circle icon ion-person" style="font-size: 55px; color: antiquewhite"></i>
                @endif
            </div>
            <div class="pull-left info">
                @if (Auth::guest())
                    <p>User</p>
                @else
                    <p>{{ Auth::user()->name}}</p>
            @endif
            <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
            <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
            </div>
        </form>
        <!-- Sidebar Menu -->

        <ul class="sidebar-menu">
            @include('layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>