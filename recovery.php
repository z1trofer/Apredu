<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="../Apredu/css/estilos.css">
  <title>Document</title>
</head>
<body class="text-center">
    
<main class="form-signin w-100 m-auto">
  <div class="container"> 
  <form action="./api/helpers/recovery.php" method="POST">
    <h1>Apredu</h1>
    <h2 class="h3 mb-3 fw-normal">Por favor, digite su correo electronico</h2>
    <div class="form-floating my-3">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email">
      <label for="floatingInput">Correo electrónico</label>
    </div>
    <button class="w-50 btn btn-lg btn-primary" type="submit">Recuperar contraseña</button>
  </form>
  </div>
</main>


    
  </body>
</html>