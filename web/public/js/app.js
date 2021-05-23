const STATUS_SUCCESS = 'success';
const STATUS_ERROR = 'error';

Vue.component("comments-tree", {
    template:
        `<div class="media">
            <img class="mr-3 rounded-circle" alt="Bootstrap Media Preview"
                 :src="comment.user.avatarfull"/>
            <div class="media-body">
                <div class="row">
                    <div class="col-8 d-flex">
                        <h5>{{comment.user.personaname}}</h5> <span>&nbsp- {{comment.time_created}}</span>&nbsp
                        <a role="button" @click="addLike(comment)">
                         <svg class="bi bi-heart-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z" clip-rule="evenodd"/>
                         </svg>
                         {{ comment.likes }}
                       </a>
                    </div>
                    <div class="col-4">
                        <div class="pull-right reply">
                            <a href="#" @click="reply(comment)"><span><i class="fa fa-reply"></i> reply</span></a>
                        </div>
                    </div>
                </div>
                {{comment.text}}
                <comments-tree class="item" v-for="(child, index) in comment.children" :comment="child" v-if="hasChild"></comments-tree>
            </div>
        </div>`,
    props: {
        comment: Object,
        userLogged: Boolean
    },
    data: function () {
        return {}
    },
    computed: {
        hasChild: function () {
            return this.comment.children && this.comment.children.length;
        }
    },
    methods: {
        addLike: (comment) => {
            if (!app.isUserLogged) {
                alert("Need login");
                return;
            }
            if (app.user_data.likes_balance === 0) {
                alert("You haven't likes");
                return;
            }
            let form = new FormData();

            form.append("id", comment.id);

            app.sendPostRequest(form, function () {
                axios.post('/comment/add_like', form)
                    .then(function (response) {
                        if (response.data.status === "success") {
                            comment.likes = response.data.likes_count;
                            app.user_data.likes_balance--;
                        }
                    })
            })
        },
        reply: (comment) => {
            app.new_comment.to_id = comment.id;
            app.new_comment.to_name = comment.user.personaname;
        }
    }
});

var app = new Vue({
    el: '#app',
    data: function () {
        return {
            email: '',
            pass: '',
            post: false,
            invalidEmail: false,
            invalidPass: false,
            invalidSum: false,
            posts: [],
            addSum: 0,
            amount: 0,
            new_comment: {
                text: "",
                to_id: 0,
                to_name: ""
            },
            boosterpacks: [],
            invalidLoginForm: {
                message: '',
                hasError: false
            },
            user_data: {},
            info_modal: {
                title: "",
                message: ""
            }
        }
    },
    computed: {
        test: function () {
            var data = [];
            return data;
        },
        isUserLogged: function () {
            return this.user_data && Object.keys(this.user_data).length;
        }
    },
    created() {
        var self = this
        axios
            .get('/post/get_all_posts')
            .then(function (response) {
                self.posts = response.data.posts;
            })

        axios
            .get('/pack/get_boosterpacks')
            .then(function (response) {
                self.boosterpacks = response.data.boosterpacks;
            })

        axios.get('/user/load')
            .then(response => {
                self.user_data = response.data.user_data;
            })
    },
    methods: {
        sendPostRequest: (formData, callback) => {
            axios.get("csrf/get_token").then((response) => {
                if (response.data.status !== "success") {
                    return;
                }
                formData.append('csrf_token', response.data.token);
                callback();
            });
        },
        logout: function () {
            axios.get("user/logout").then((response) => {
                if (response.data.status === "success") {
                    this.user_data = {};
                }
            });
        },
        logIn: function () {
            var self = this;

            self.invalidEmail = false;
            self.invalidPass = false;
            self.invalidLoginForm.hasError = false;

            if (self.email === '') {
                self.invalidEmail = true
            } else if (self.pass === '') {
                self.invalidPass = true
            } else {
                form = new FormData();
                form.append("email", self.email);
                form.append("password", self.pass);

                self.sendPostRequest(form, () => {
                    axios.post('/user/login', form)
                        .then(function (response) {
                            if (response.data.user_data) {
                                self.user_data = response.data.user_data;

                                setTimeout(function () {
                                    $('#loginModal').modal('hide');
                                }, 0);
                                return;
                            }
                            if (response.data.status !== "success") {
                                self.invalidLoginForm.message = response.data.error_message;
                                self.invalidLoginForm.hasError = true;
                            } else {
                                setTimeout(function () {
                                    $('#loginModal').modal('hide');
                                }, 500);
                            }
                        })
                })
            }
        },
        addComment: function (id) {
            var self = this;
            if (Object.keys(self.new_comment).length) {

                var comment = new FormData();
                comment.append('post_id', id);
                comment.append('comment_ext', self.new_comment.text);
                comment.append('reply_id', self.new_comment.to_id);

                self.sendPostRequest(comment, function () {
                    axios.post(
                        '/comment/add',
                        comment
                    ).then(function (response) {
                        if (response.data.status === "success") {
                            self.post.comments = response.data.comments;
                            self.new_comment = {
                                text: "",
                                to_id: 0,
                                to_name: ""
                            }
                        }
                    });
                });
            }
        },
        refill: function () {
            var self = this;
            let userAddSum = parseFloat(self.addSum);
            if (Number.isNaN(userAddSum)) {
                userAddSum = 0;
            }
            self.addSum = userAddSum;
            if (self.addSum === 0) {
                self.invalidSum = true
            } else {
                self.invalidSum = false
                sum = new FormData();
                sum.append('sum', self.addSum);
                self.sendPostRequest(sum, () => {
                    axios.post('/user/add_money', sum)
                        .then(function (response) {
                            if (response.data.status === "success") {
                                self.user_data.wallet_balance = response.data.new_balance;
                                self.addSum = 0;
                            }
                            setTimeout(function () {
                                $('#addModal').modal('hide');
                            }, 500);
                        })
                })
            }
        },
        openPost: function (id) {
            var self = this;
            axios
                .get('/post/get_post/' + id)
                .then(function (response) {
                    self.post = response.data.post;
                    if (self.post) {
                        setTimeout(function () {
                            $('#postModal').modal('show');
                        }, 500);
                    }
                })
        },
        addPostLike: function (post) {
            var self = this;

            if (!self.isUserLogged) {
                alert("Need login");
                return;
            }
            if (self.user_data.likes_balance === 0) {
                alert("You haven't likes");
                return;
            }
            let form = new FormData();

            form.append("id", post.id);

            self.sendPostRequest(form, function () {
                axios.post('/post/add_like', form)
                    .then(function (response) {
                        if (response.data.status === "success") {
                            self.post.likes = response.data.likes_count;
                            self.user_data.likes_balance--;
                        }
                    })
            })
        },
        buyPack: function (pack) {
            var self = this;
            var packForm = new FormData();

            if (!self.isUserLogged) {
                $("#loginModal").modal('show');
                return;
            }

            if (self.user_data.wallet_balance < pack.price) {
                self.showInfoModal("You haven't money for this pack", false);
                return;
            }

            packForm.append('id', pack.id);

            self.sendPostRequest(packForm, ()=> {
                axios.post('/pack/buy_boosterpack', packForm)
                    .then(function (response) {
                        if (response.data.status === "success") {
                            self.amount = response.data.likes_win;
                            setTimeout(function () {
                                $('#amountModal').modal('show');
                                self.user_data.wallet_balance = response.data.user_balance;
                                self.user_data.likes_balance = response.data.user_likes;
                            }, 500);
                        } else {
                            self.showInfoModal(response.data.error_message);
                        }
                    });
            })
        },
        showInfoModal: function (message, title) {
            this.info_modal.title = title;
            this.info_modal.message = message;

            $("#infoModal").modal("show");
        }
    }
});

