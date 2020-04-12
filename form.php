<html>
<head>
  <title>Add a Piece of Trash</title>
      <!--<link rel ="stylesheet" type = "text/css" href="loginstyle.css">-->
<body>
  <header>
      <h1>TrashTracker</h1>
  </header>
  <div class="blurb">
      <p>An intelligent tracker to aid in the cleanup of discarded waste</p>
  </div>

  <style>
  body{
    margin: 0;
    padding: 0;
    background-size: cover;
  background-color: deepskyblue;
      background-position:center;
    font-family: sans-serif;

  }
  .blurb {
    display: flex;
    position: static;
    /* top: 200px;
    left: 50px; */
    color: rgb(0, 0, 0);
    height: 100px;
    justify-content: center;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
    font-size: 20px;
    background-color: deepskyblue;
    justify-content: center;
  }
  header {
    display: flex;
    position: static;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    justify-content: center;
    line-height: 50px;
    background-color: rgb(38, 104, 36);
    color: rgb(255, 255, 255);
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  }
  .loginbox input[type="text"]
  {
    border: none;
    border-bottom: 1px solid #fff;
    background: transparent;
    outline: none;
    height: 40px;
    font-size: 16px;
  }

  .loginbox
  {
    width: 320 px;
    height: 420 px;
    background-color: deepskyblue;
    top: 50%;
    left: 50%;
    position: absolute;
    transform: translate(-50%, -50%);
    box-sizing: border-box;
    padding: 80 px 30 px;
  }
  .loginbox input[type="submit"]
  {
    border: none;
    outline: none;
    height: 40px;
    background-color: rgb(24, 87, 244);
    color: #fff;
    font-size: 18px;
    border-radius: 20px;
  }

  .loginbox input[type ="submit"]
  {
      cursor: pointer;
      background-color: 	rgb(255,255,255)
      color: #000;
  }

  .avatar{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    position: absolute;
    top: -90px;
    left: calc(50% - 50px);
  }

  </style>
        <div class = "loginbox">
            <div class = headerImage>
              <!--<img src = "https://image.freepik.com/free-vector/cartoon-cute-earth-with-tree-head-hand-drawn_41992-837.jpg" class="avatar">-->
            </div>
            <form method="post" action="TTmap.php">
                    <div style="text-align:center;">
                  <font face = "Lucida Sans">
                    <img src = "https://image.freepik.com/free-vector/cartoon-cute-earth-with-tree-head-hand-drawn_41992-837.jpg" class="avatar">
                  <p>Enter your coordinates here:</p>
                  <p>Latitude</p>
                  <input type="text" name = "lat" placeholder="Latitude">
                  <p>Longitude</p>
                  <input type = "text" name ="long" placeholder="Longitude"><br>
                <input type = "submit" name="" value= "Enter Trash"> </div>
                </font>
                  <!-- <div class = buttonWrapper>
                    <div id="button">
                      <ul>
                          <li><a href="something.html">Login</a></li>
                      </ul>
                    </div>
                  </div> -->
             </form>
          </div>
  </body>
  </head>
  </html>
