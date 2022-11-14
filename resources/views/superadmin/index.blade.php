<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{ config('app.name', 'TEST') }}</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
        integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

</head>

<body>
    <div class="wrapper">

        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header ">
                <!--  <h3>Bootstrap Sidebar</h3> -->
                <img src="{{ asset('assets/imgs/test.gif') }}" alt="..." class="img-thumbnail"
                    style="max-height: 100px; max-width: 100px;">
            </div>


            <ul class="list-unstyled components">
                {{-- DASHBOARD --}}
                <li>
                    <a href="#">Dashboard</a>
                </li>
                {{-- EMPLOYEES --}}
                <li>
                    <a href="#employeeSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Employees</a>
                    <ul class="collapse list-unstyled" id="employeeSubmenu">
                        <li>
                            <a href="/superadmin/employees/add">Add Employee</a>
                        </li>
                        <li>
                            <a href="/superadmin/employees/view">View Employees</a>
                        </li>
                    </ul>
                </li>
                {{-- USERS --}}
                <li>
                    <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Users</a>
                    <ul class="collapse list-unstyled" id="userSubmenu">
                        <li>
                            <a href="#">Add User</a>
                        </li>
                        <li>
                            <a href="/superadmin/users/view">View Users</a>
                        </li>
                    </ul>
                </li>
                {{-- CUSTOMERS --}}
                <li>
                    <a href="#customerSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Customer</a>
                    <ul class="collapse list-unstyled" id="customerSubmenu">
                        <li>
                            <a href="#">Register Customer</a>
                        </li>
                        <li>
                            <a href="#">Manage Customers</a>
                        </li>
                    </ul>
                </li>
                {{-- PRODUCTS --}}
                <li>
                    <a href="#productSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Products</a>
                    <ul class="collapse list-unstyled" id="productSubmenu">
                        <li>
                            <a href="/superadmin/products/add">Add Products</a>
                        </li>
                        <li>
                            <a href="/superadmin/products/view">View Products</a>
                        </li>
                    </ul>
                </li>
                {{-- BRANCHES --}}
                <li>
                    <a href="#branchSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Branches</a>
                    <ul class="collapse list-unstyled" id="branchSubmenu">
                        <li>
                            <a href="#">Add Branch</a>
                        </li>
                        <li>
                            <a href="/superadmin/branches/view">Manage Branches</a>
                        </li>
                    </ul>
                </li>
                {{-- TRANSACTIONS --}}
                <li>
                    <a href="#transactionSubmenu" data-toggle="collapse" aria-expanded="false"
                        class="dropdown-toggle">Transactions</a>
                    <ul class="collapse list-unstyled" id="transactionSubmenu">
                        <li>
                            <a href="/superadmin/transactions/completed">Completed Transactions</a>
                        </li>
                        <li>
                            <a href="/superadmin/transactions/pending">Pending Transactions</a>
                        </li>
                    </ul>
                </li>
                {{-- REPORTS --}}
                <li>
                    <a href="#">Reports</a>
                </li>
            </ul>

            <ul class="list-unstyled components">
                <!-- <ul class="list-unstyled CTAs"> -->
                <li>
                    <a href="#">Profile</a>
                </li>
                <li>
                    <a href="#">Logout</a>
                </li>
            </ul>
        </nav>
        
        @yield('section')

        <!-- Font Awesome JS -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
            integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
        </script>
        <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"
            integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
        </script>

        <!-- jQuery CDN - Slim version (=without AJAX) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
        <!-- Popper.JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
            integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous">
        </script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
            integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous">
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#sidebarCollapse').on('click', function() {
                    $('#sidebar').toggleClass('active');
                });
            });
        </script>
</body>

</html>
