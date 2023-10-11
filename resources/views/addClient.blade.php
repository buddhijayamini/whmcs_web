<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 Form Example Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
  <div class="container mt-4">
  @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif
  <div class="card">
    <div class="card-header text-center font-weight-bold">
      WHMCS - Add Client
    </div>
    <div class="card-body">
      <form name="add-client-form" id="add-client-form" method="post" action="{{ route('create-client')}}">
       @csrf
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" id="firstname" name="firstname" class="form-control" required="">
        </div>
        <div class="form-group">
          <label for="lastname">Last Name</label>
          <input type="text" id="lastname" name="lastname" class="form-control" required="">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="address1">Address 01</label>
            <input type="text" id="address1" name="address1" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" id="city" name="city" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="state">State</label>
            <input type="text" id="state" name="state" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="postcode">PostCode</label>
            <input type="text" id="postcode" name="postcode" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="country">Country</label>
            <input type="text" id="country" name="country" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="phonenumber">Phone Number</label>
            <input type="text" id="phonenumber" name="phonenumber" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="password2">Conform Password</label>
            <input type="password" id="password2" name="password2" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="--clientip">Client Tip</label>
            <input type="text" id="clientip" name="clientip" class="form-control" required="">
          </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
