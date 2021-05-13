<?php

use Model\User_model;

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Test Task</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/app.css?v=<?= filemtime(FCPATH . '/css/app.css') ?>">
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
</head>
<body>
<div id="app">
  <div class="header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01"
              aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <li class="nav-item">
            <? if (User_model::is_logged()) {?>
              <a href="/main_page/logout" class="btn btn-primary my-2 my-sm-0"
                 data-target="#loginModal">Log out, <?= $user->personaname?>
              </a>
            <? } else {?>
              <button type="button" class="btn btn-success my-2 my-sm-0" type="submit" data-toggle="modal"
                      data-target="#loginModal">Log IN
              </button>
            <? } ?>
        </li>
        <li class="nav-item">
            <?  if (User_model::is_logged()) {?>
              <button type="button" class="btn btn-success my-2 my-sm-0" type="submit" data-toggle="modal"
                      data-target="#addModal">Add balance
              </button>
            <? }?>
        </li>
        <li class="nav-item">
            <?  if (User_model::is_logged()) {?>
                <a href="" role="button">
                    Likes:
                </a>
            <? }?>
        </li>
      </div>
<!--      <div class="collapse navbar-collapse" id="navbarTogglerDemo01">-->
<!--        <li class="nav-item">-->
<!--            --><?// if (User_model::is_logged()) {?>
<!--              <button type="button" class="btn btn-primary my-2 my-sm-0" type="submit" data-toggle="modal"-->
<!--                      data-target="#loginModal">Log in-->
<!--              </button>-->
<!--            --><?// } else {?>
<!--              <button type="button" class="btn btn-danger my-2 my-sm-0" href="/logout">Log out-->
<!--              </button>-->
<!--            --><?// } ?>
<!--        </li>-->
<!--        <li class="nav-item">-->
<!--          <button type="button" class="btn btn-success my-2 my-sm-0" type="submit" data-toggle="modal"-->
<!--                  data-target="#addModal">Add balance-->
<!--          </button>-->
<!--        </li>-->
<!--      </div>-->
    </nav>
  </div>
  <div class="main">
    <div class="posts">
      <h1 class="text-center">Posts</h1>
      <div class="container">
        <div class="row">
          <div class="col-4" v-for="post in posts" v-if="posts">
            <div class="card">
              <img :src="post.img + '?v=<?= filemtime(FCPATH . '/js/app.js') ?>'" class="card-img-top" alt="Photo">
              <div class="card-body">
                <h5 class="card-title">Post - {{post.id}}</h5>
                <p class="card-text">{{post.text}}</p>
                <button type="button" class="btn btn-outline-success my-2 my-sm-0" @click="openPost(post.id)">Open post
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="boosterpacks">
        <h1 class="text-center">Boosterpack's</h1>
        <div class="container">
          <div class="row">
            <div class="col-4" v-for="boosterpack in boosterpacks" v-if="boosterpacks">
              <div class="card">
                <img :src="'/images/box.png'" class="card-img-top" alt="Photo">
                <div class="card-body">
                  <button type="button" class="btn btn-outline-success my-2 my-sm-0" @click="buyPack(boosterpack.id)">Buy boosterpack {{boosterpack.price}}$
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <!-- Modal -->
    <?php require_once "modals/login.php"?>
    <!-- Modal -->
    <?php require_once "modals/post.php"?>
  <!-- Modal -->
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add money</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="exampleInputEmail1">Enter sum</label>
              <input type="text" class="form-control" id="addBalance" v-model="addSum" required>
              <div class="invalid-feedback" v-if="invalidSum">
                Please write a sum.
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" @click="refill">Add</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal fade" id="amountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Amount</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h2 class="text-center">Likes: {{amount}}</h2>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
<script src="/js/app.js?v=<?= filemtime(FCPATH . '/js/app.js') ?>"></script>
</body>
</html>


