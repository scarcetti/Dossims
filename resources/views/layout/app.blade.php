<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{config('app.name',"TEST")}}</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    
</head>

<body>
    <div class="wrapper">
        
        <!-- Sidebar  -->
        <nav id="sidebar">
          <div class="sidebar-header ">
             <!--  <h3>Bootstrap Sidebar</h3> -->
             <img src="{{asset('assets/imgs/test.gif')}}" alt="..." class="img-thumbnail" style="max-height: 100px; max-width: 100px;">
          </div>
          
      
          <ul class="list-unstyled components">
              <li>
                  <a href="#">Dashboard</a>
              </li>
              <li>
                  <a href="#employeeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Employees</a>
                  <ul class="collapse list-unstyled" id="employeeSubmenu">
                      <li>
                          <a href="#">Add Employee</a>
                      </li>
                      <li>
                          <a href="#">View Employees</a>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="#userSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Users</a>
                  <ul class="collapse list-unstyled" id="userSubmenu">
                      <li>
                          <a href="#">Add User</a>
                      </li>
                      <li>
                          <a href="#">View Users</a>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="#productSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Products</a>
                  <ul class="collapse list-unstyled" id="productSubmenu">
                      <li>
                          <a href="#">Add Products</a>
                      </li>
                      <li>
                          <a href="#">View Products</a>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="#branchSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Branches</a>
                  <ul class="collapse list-unstyled" id="branchSubmenu">
                      <li>
                          <a href="#">Add Branch</a>
                      </li>
                      <li>
                          <a href="#">Manage Branches</a>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="#transactionSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Transactions</a>
                  <ul class="collapse list-unstyled" id="transactionSubmenu">
                      <li>
                          <a href="#">Completed Transactions</a>
                      </li>
                      <li>
                          <a href="#">Pending Transactions</a>
                      </li>
                  </ul>
              </li>
              <li>
                  <a href="#customerSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Customer</a>
                  <ul class="collapse list-unstyled" id="customerSubmenu">
                      <li>
                          <a href="#">Register Customer</a>
                      </li>
                      <li>
                          <a href="#">Manage Customers</a>
                      </li>
                  </ul>
              </li>
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

    @yield('script')
</body>

</html>