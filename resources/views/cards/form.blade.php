<div class="modal fade" id="modal-form" tabindex="1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog"><!-- Log on to codeastro.com for more projects! -->
        <div class="modal-content">
            <form  id="form-item" method="post" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data" >
                {{ csrf_field() }} {{ method_field('POST') }}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title"></h3>
                </div>


                <div class="modal-body">
                    <input type="hidden" id="id" name="id">


                    <div class="box-body" id="sssssssssss">
                        <div class="form-group">
                            <label >Number</label>
                            <input type="text" class="form-control" id="number" name="number"  autofocus required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Working Days</label>
                            <input type="text" class="form-control date" id="working_days" placeholder="Pick the multiple dates" name="working_days"   required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Usage Hours</label>
                            <select id="multiselect" class="form-control" name="usage_hours[]" multiple="multiple" required>
                                <option value="1" >9:00-10:00</option>
                                <option value="2" >10:00-11:00</option>
                                <option value="3" >11:00-12:00</option>
                                <option value="4">12:00-13:00</option>
                                <option value="5">13:00-14:00</option>
                                <option value="6">14:00-15:00</option>
                                <option value="7">15:00-16:00</option>
                                <option value="8">16:00-17:00</option>
                                <option value="9">17:00-18:00</option>
                                <option value="10">18:00-19:00</option>
                                <option value="11">19:00-20:00</option>
                                <option value="12">20:00-21:00</option>
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>

                        
                        <div class="form-group">
                            <label >Bus Lines</label>
                            <input type="text" class="form-control" id="bus_lines" name="bus_lines"   required>
                            <span class="help-block with-errors"></span>
                        </div>

                        <div class="form-group">
                            <label >Category</label>
                            {!! Form::select('category_id', $category, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Category --', 'id' => 'category_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>
                        <div class="form-group">
                            <label >Customer</label>
                            {!! Form::select('customer_id', $customer, null, ['class' => 'form-control select', 'placeholder' => '-- Choose Customer --', 'id' => 'customer_id', 'required']) !!}
                            <span class="help-block with-errors"></span>
                        </div>

                    </div>
                    <!-- /.box-body -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- Log on to codeastro.com for more projects! -->
<!-- /.modal -->
