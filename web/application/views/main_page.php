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
              <button type="button" @click="logout()" class="btn btn-primary my-2 my-sm-0" v-if="isUserLogged">Log out, {{user_data.personaname}}
              </button>
              <button type="button" class="btn btn-success my-2 my-sm-0" data-toggle="modal" v-else
                      data-target="#loginModal">Log IN
              </button>
        </li>
        <li class="nav-item">
              <button type="button" class="btn btn-success my-2 my-sm-0" data-toggle="modal" v-if="isUserLogged"
                      data-target="#addModal">Add balance
              </button>
        </li>
        <li class="nav-item">
                <a href="#" role="button" v-if="isUserLogged">
                    Likes: {{user_data.likes_balance}}
                </a>
        </li>
          <li class="nav-item">
              <a href="#" role="button" v-if="isUserLogged">
                  Balance: {{user_data.wallet_balance}}
              </a>
          </li>
      </div>
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
                  <button type="button" class="btn btn-outline-success my-2 my-sm-0" @click="buyPack(boosterpack)">Buy boosterpack {{boosterpack.price}}$
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
    <?php require_once "modals/payment.php"?>
    <!-- Modal -->
    <?php require_once "modals/pack_result.php"?>
    <!-- Modal -->
    <?php require_once "modals/info_modal.php"?>
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


