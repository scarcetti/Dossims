@extends('frontdesk.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Products</h2>
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
                        <th scope="col">Product</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($x = 1; $x <=5; $x+=1) { ?>
                    <tr>
                        <th scope="row">{{ $x }}</th>
                        <td>Steel Roof</td>
                        <td>Roofing</td>
                        <td>218</td>
                        <td>-</td>
                        <td>Red roof, 5m x 1.5m</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
@endsection
