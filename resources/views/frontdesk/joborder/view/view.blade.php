@extends('frontdesk.index')

@section('section')
        <div class="container-fluid d-flex align-items-center">
            <h2 class="text-primary">Job Order</h2>
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
                        <th scope="col">Contract Start</th>
                        <th scope="col">Contract End</th>
                        <th scope="col">Time in</th>
                        <th scope="col">Time out</th>
                        <th scope="col">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($x = 1; $x <=5; $x+=1) { ?>
                    <tr>
                        <th scope="row">{{ $x }}</th>
                        <td>Now</td>
                        <td>Later</td>
                        <td>rn</td>
                        <td>after 5 mins kek</td>
                        <td>218 per hr? idk</td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
@endsection
