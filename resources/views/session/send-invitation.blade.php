@extends('layouts.session')
@section('content')
<div class="container">
    <div class="mt-5">
        <h2>Send Invitation</h2>

        <form id="inviteForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" readonly value="{{$user->email}}" id="email" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
            </div>

            <div class="mb-3">
                <label for="invitation_url" class="form-label">Invitation Nosphere Healing URL:</label>
                <input type="url" name="invitation_url" id="invitation_url" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid URL.</div>
            </div>

            <button type="submit" id="send_invitation" class="btn btn-primary">Send Invitation</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#inviteForm").submit(function(event) {
            event.preventDefault();


            let email = $("#email").val();
            let invitation_url = $("#invitation_url").val();

            // Basic validation
            if (!validateEmail(email)) {
                $("#email").addClass("is-invalid");
                return;
            } else {
                $("#email").removeClass("is-invalid");
            }

            if (!validateURL(invitation_url)) {
                $("#invitation_url").addClass("is-invalid");
                return;
            } else {
                $("#invitation_url").removeClass("is-invalid");
            }

            $('#send_invitation').prop("disabled", true);

            let formData = {
                _token: "{{ csrf_token() }}",
                email: email,
                invitation_url: invitation_url,
                user_id: '{{ $user->id }}'
            };

            $.ajax({
                url: "{{ route('sendinvitation.email') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log('Submitted Form');

                    $("#responseMessage").html(
                        `<div class="alert alert-success">${response.message}</div>`
                    );
                    setTimeout(function() {
                        window.location.href = "{{route('start-session', $user->id)}}"; // Replace with your target URL
                    }, 1000);
                    $("#inviteForm")[0].reset();
                    $('#send_invitation').prop("disabled", false);
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.";
                    $("#responseMessage").html(`<div class="alert alert-danger">${errorMsg}</div>`);
                }
            });
        });

        // Email validation function
        function validateEmail(email) {
            let re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(email);
        }

        // URL validation function
        function validateURL(url) {
            let re = /^(https?:\/\/)?([\w\-])+(\.[\w\-]+)+[/#?]?.*$/;
            return re.test(url);
        }
    });
</script>

@endsection
