@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="notifications"></div>
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Real time notification tutorial!') }}
                        <a href="{{ route('show-chat') }}">Go to chat</a>
                    </div>
                </div>

                <div class="card p-3">
                    <p class="text-primary">All users</p>
                    <div id="users">

                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection

@push('scripts')
    <script>
        console.log("hello");
        window.Echo.private('notifications')
            .listen('UserSessionChanged', (e) => {
                let notifications = document.getElementById('notifications');
                notifications.innerHTML = `<div class="alert alert-${e.type} alert-dismissible fade show" role="alert">
                                            <strong>${e.message}</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>`;
            });

        window.axios.get('api/users')
            .then((response) => {
                let userEl = document.getElementById("users");
                let users = response.data.users;

                users.forEach((user, ind) => {
                    userEl.innerHTML += `<p id="${++ind}">${user.name}</p>`;
                });

            })

        window.Echo.channel('users')
            .listen('UserCreatedEvent', (e) => {
                let userEl = document.getElementById("users");
                let user = e.user;

                let newUser = document.createElement('p');
                newUser.setAttribute('id', user.id);
                newUser.innerText = user.name;

                userEl.appendChild(newUser);
            })
            .listen('UserUpdatedEvent', (e) => {
                let user = document.getElementById(e.id);
                user.innerHTML = e.name;
            })
            .listen('UserDeletedEvent', (e) => {
                let user = document.getElementById(e.user.id);
                user.parentNode.removeChild(user);
            });
    </script>
@endpush
