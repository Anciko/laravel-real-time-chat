@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-10">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            Chat
                        </div>
                        <div class="card-body" style="height: 400px;" id="messages">

                        </div>
                    </div>

                    <div class="card p-2 mt-2">
                        <form action="" method="POST">
                            @csrf
                            <div class="d-flex">
                                <input type="text" class="form-control" id="messageTxt" name="message">
                                <button class="btn btn-primary ms-2" id="sendBtn" type="submit">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-header">Online Now</div>
                        <div class="card-body">
                            <div id="online-users" class="text-info">

                            </div>
                        </div>
                    </div>
                </div>

            </div>



        </div>


    </div>
@endsection

@push('scripts')
    <script>
        let onlineUsersEl = document.getElementById('online-users');

        Echo.join('chat')
            .here((users) => {
                users.forEach(user => {
                    let onlineuser = document.createElement('p');
                    onlineuser.setAttribute('id', user.id);
                    onlineuser.innerHTML = user.name;

                    onlineUsersEl.appendChild(onlineuser);
                });
            })
            .joining(user => {
                let onlineuser = document.createElement('p');
                onlineuser.setAttribute('id', user.id);
                onlineuser.innerHTML = user.name;

                onlineUsersEl.appendChild(onlineuser);
            })
            .leaving(user => {
                let onlineuser = document.getElementById(user.id);
                onlineuser.parentNode.removeChild(onlineuser);
            })
            .listen('MessageSentEvent', (e) => {
                let messageEl = document.getElementById('messages');
                let message = document.createElement('p');

                message.innerHTML = e.user.name + ": " + e.message;
                messageEl.appendChild(message);
            })
    </script>

    <script>
        let messageEl = document.getElementById('messageTxt');
        let sendBtn = document.getElementById('sendBtn');

        sendBtn.addEventListener('click', (e) => {
            e.preventDefault();

            window.axios.post('chat/message', {
                message: messageEl.value
            });
            messageEl.value = "";
        });
    </script>
@endpush
