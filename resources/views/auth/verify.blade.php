<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel</title>
</head>
<body>
    <form action="{{ route( 'verifyUser' ) }}" method="POST">
      
        <div class="form-group">
    
            <input type="hidden" class="form-control" value="{{ $email }}" name="email" >
          </div> 

        <div class="form-group">
          <label for="exampleInputPassword1">Verify Pin</label>
          <input type="text" class="form-control"  name="pin" placeholder="Enter Pin">
        </div>
    
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    
</body>
</html>