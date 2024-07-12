<div id="defaultdays-form" >
  <form role="form"   method="post" data-toggle="validator" enctype="multipart/form-data">
  {{ csrf_field() }}  {{ method_field('POST') }}
      <div class="box " style="padding: 10px;">
      <label >Working Days</label>
      <input type="text" class="form-control date" id="working_day" placeholder="Pick the multiple dates" name="working_days" style="margin-top: 10px; margin-bottom: 10px;"   required>
      <button type="submit" class="btn btn-success">Save</button>
    </div>
  </form>
</div>