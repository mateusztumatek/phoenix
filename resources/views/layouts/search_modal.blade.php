<div class="modal fade my-modal" id="search_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block">
                <a class="close-modal" data-dismiss="modal">X</a>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 text-center">
                    <h3 class="white-color">Wpisz nazwę elementu</h3>
                </div>
                <form method="get" action="{{url('search')}}" class="form-inline md-form form-sm active-pink-2 mt-2 w-100 mt-3 search-form" style="margin: 0px !important;">
                    <input class="form-control" name="search" style="width: 97%">
                    <label class="vertical-align justify-content-center white-color" style="width: 3%"><i onclick="$('.search-form').submit()" style="font-size: 1.5rem" class="fa fa-search"></i></label>
                </form>
            </div>
        </div>
    </div>
</div>