<div class="pos-tab-content">
{!! Form::open(['method' => 'post', 'url' => '/send_sms','files' => true])!!}
  
  <label for="to">Sender Number:</label><br>
  <input type="text" name="to"><br><br>
  
  <label for="msg">Message Body:</label><br>
  <textarea type="text" name="msg" value="Message"></textarea><br><br>
  
  <input type="submit" value="Submit">
{!!Form::close()!!}
</div>