<div class="card">
  <div class="card-header">
        Register User
  </div>
  <div class="card-body">
      <form method="POST" action="<?php  $_SERVER['PHP_SELF']; ?>">
          <div class="form-group">
              <label>Name</label>
              <input type="text" name="name" class="form-control">
          </div>
          <div class="form-group">
              <label>Email</label>
              <input type="text" name="email" class="form-control">
          </div>
          <div class="form-group">
              <label>Password</label>
              <input type="password" name="password" class="form-control">
          </div>
          <input class="btn btn-primary" name="submit" type="submit" value="Submit">
      </form>
  </div>
</div>