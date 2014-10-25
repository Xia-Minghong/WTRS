<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include('nav_header')



            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">

                        {{--<li>--}}
                            {{--<a @if(Request::is('admin')) class="active" @endif href="/admin"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>--}}

                        {{--</li>--}}
                        <li class="active">
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Reports<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a @if(Request::is('admin/reports/particular')) class="active" @endif href="/admin/reports/particular">Teachers' Particulars</a>
                                </li>
                                <li>
                                    <a @if(Request::is('admin/reports/MClist')) class="active" @endif href="/admin/reports/MClist">MC List</a>
                                </li>
                                <li>
                                    <a @if(Request::is('admin/reports/MCscore')) class="active" @endif href="/admin/reports/MCscore">MC Scores</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        {{--<li>--}}
                            {{--<a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="forms.html"><i class="fa fa-wrench fa-fw"></i> Forms</a>--}}
                        {{--</li>--}}
                        <li class="active">
                            <a href="#"><i class="fa fa-edit fa-fw"></i> Actions<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a @if(Request::is('admin/actions/createMC')) class="active" @endif href="/admin/actions/createMC">Create New MC</a>
                                </li>
                                <li>
                                    <a @if(Request::is('admin/actions/generaterelief')) class="active" @endif href="/admin/actions/generaterelief"><strong>Generate Relief Timetables</strong></a>
                                </li>
                                <li>
                                    <a @if(Request::is('admin/reports/timetable')) class="active" @endif href="/admin/reports/timetable">Additional Timetables</a>
                                </li>
                                <li>
                                    <a @if(Request::is('admin/actions/changepwd')) class="active" @endif href="/admin/actions/changepwd">Change Admin Password</a>
                                </li>

                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        {{--<li>--}}
                            {{--<a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>--}}
                            {{--<ul class="nav nav-second-level">--}}
                                {{--<li>--}}
                                    {{--<a href="#">Second Level Item</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">Second Level Item</a>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                    {{--<a href="#">Third Level <span class="fa arrow"></span></a>--}}
                                    {{--<ul class="nav nav-third-level">--}}
                                        {{--<li>--}}
                                            {{--<a href="#">Third Level Item</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">Third Level Item</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">Third Level Item</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--<a href="#">Third Level Item</a>--}}
                                        {{--</li>--}}
                                    {{--</ul>--}}
                                    {{--<!-- /.nav-third-level -->--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                            {{--<!-- /.nav-second-level -->--}}
                        {{--</li>--}}
                        <li>
                            <a @if(Request::is('admin/actions/upload')) class="active" @endif href="/admin/actions/upload"><i class="fa fa-files-o fa-fw"></i> Upload Timetables/Particulars</a>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>