<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fead Idea</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        .row-buttons {
            opacity: 0;
            transition: opacity 150ms;
        }

        .idea-row:hover .row-buttons {
            opacity: 1;
        }

        .alert-warning {
            background: #fefbed;
            border: 0;
        }

        @media (max-width: 991px) {
            .row-buttons {
                opacity: 1;
            }
        }
    </style>

    <!-- jQuery and JS bundle w/ Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
</head>
<body>
    <div id="app" class="container my-5">
        <div class="row">
            <div class="col-12">
                <div class="mb-5">
                    <h2 class="text-center mb-0">fead<span class="text-primary">idea</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="mb-3 d-lg-none">
                    @include('tips')
                </div>
                <div class="card card-body d-flex flex-row mb-3">
                    <input type="text" class="form-control" placeholder="Search" v-model="search" @keyup.enter="fetchIdeas">
                    <button class="btn btn-primary ml-2" @click="fetchIdeas" :disabled="loadingFetch">Generate</button>
                </div>
                <div class="card mb-3">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Idea</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="idea-row" v-for="(idea, key) in ideas" :key="key">
                                    <td>@{{ idea.name }}</td>
                                    <td class="text-right">
                                        <div v-if="idea.domains.length !== 0">
                                            <div v-for="(available, domain) in idea.domains" class="small" :class="available ? 'text-success' : 'text-muted'">
                                                @{{ domain }}
                                            </div>
                                        </div>
                                        <div class="row-buttons" v-else>
                                            <button class="btn btn-sm btn-primary" @click="checkDomain(idea.name)" :disabled="loadingButton">Check domains</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                @include('tips')
            </div>
        </div>
    </div>
    <script>
        new Vue({
            el: '#app',

            data() {
                return {
                    search: "<><>",
                    ideas: [],
                    awaitingSearch: false,
                    loadingButton: false,
                    loadingFetch: true
                };
            },

            mounted: function () {
                this.fetchIdeas();
            },

            methods: {
                fetchIdeas: function () {
                    this.loadingFetch = true;

                    $.ajax({
                        url: '/ideas',
                        data: { q: this.search },
                        success: function (ideas) {
                            this.ideas = ideas;
                            this.loadingFetch = false;
                        }.bind(this)
                    });
                },

                checkDomain: function (idea) {
                    this.loadingButton = true;

                    $.ajax({
                        url: '/check-domain/' + idea,
                        type: 'POST',
                        success: function (response) {
                            var findIdea = this.ideas
                                .map(function (value, index) {
                                    return [value.name, index];
                                })
                                .filter(function (value) {
                                    return idea === value[0];
                                })[0];

                            this.$set(this.ideas, findIdea[1], {
                                name: findIdea[0],
                                domains: response
                            });
                        }.bind(this),
                        complete: function () {
                            this.loadingButton = false;
                        }.bind(this)
                    });
                }
            },

            watch: {
                search: function (val) {
                    if (!this.awaitingSearch) {
                        setTimeout(() => {
                            this.fetchIdeas();
                            this.awaitingSearch = false;
                        }, 500);
                    }

                    this.awaitingSearch = true;
                },
            },
        });
    </script>
</body>
</html>
