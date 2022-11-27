@extends('superadmin.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Completed Transactions</h2>
            <!--   <div class="d-flex gap-3 ms-auto">
                            <a href="" class="btn btn-outline-danger h-100 ms-auto">Back</a>
                            <a href="" class="btn btn-outline-success h-100">Add Student</a>
                        </div> -->
        </div>
        <div class="card p-3">

            <table class="table table-striped">
                <thead id="mainBG" style="color: white">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Customer</th>
                        <th scope="col">Front Desk</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($x = 1; $x <=5; $x+=1) { ?>
                    <tr>
                        <th scope="row">{{ $x }}</th>
                        <td>11/11/2022</td>
                        <td>Josh Petty</td>
                        <td>Elon Moist</td>
                        <td>Item1, Item2, Item 3</td>
                        <td>₱69, 420</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Manage
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">Action 1</a>
                                    <a class="dropdown-item" href="#">Action 2</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
@endsection