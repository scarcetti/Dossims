<style type="text/css">
    table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        /*     paper-si */
        line-height: 25px;
    }

    div {
        /*    border: solid;*/
    }

    .dflex {
        display: grid;
    }

    .row {
        flex-direction: row;
    }

    .column {
        /*    float: left;*/
        flex-direction: column;
        position: relative;
    }

    .sec1 {
        width: 30%;
        position: absolute;
        left: 0;
        top: 0;
    }

    .sec2 {
        width: 68%;
        position: absolute;
        right: 0;
        top: 0;
    }

    .sec3 {
        width: 70%;
        padding-left: 10px;
        /*    background-color: aqua;*/
        float: right;
    }

    .nt {
        border-top: 0;
    }

    .nb {
        border-bottom: 0;
    }

    .nob {
        border: 0;
    }

    .ob {
        border-left: 0;
        border-top: 0;
        border-right: 0;
    }

    th,
    td {
        border: solid 1px;
        height: 28px;
    }
    .fr {
        float: right
    }

    input[type="checkbox"] {}
</style>
<div style="position: relative; height: 230px;">
    @include('printout.charge-invoice.section1')
</div>
<div style="position: relative; height: 400px;">
    @include('printout.charge-invoice.section2')
</div>



