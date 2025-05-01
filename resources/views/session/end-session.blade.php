@extends('layouts.session')
@section('content')
<div class="container">
    <div class="mt-5">
        <h2>Your session has been ended.</h2>

        <form id="recordingForm">
            @csrf
            <div class="mb-3">
                <label for="recording_url" class="form-label">Recording URL:</label>
                <input type="url" name="recording_url" id="recording_url" class="form-control" required>
                <div class="invalid-feedback">Please enter a valid URL.</div>
            </div>

            <button type="submit" id="update_submit" class="btn btn-primary">UPDATE</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#recordingForm").submit(function(event) {
            event.preventDefault();
            //let email = $("#email").val();
            let recording_url = $("#recording_url").val();

            if (!validateURL(recording_url)) {
                $("#recording_url").addClass("is-invalid");
                return;
            } else {
                $("#recording_url").removeClass("is-invalid");
            }

            $('#update_submit').prop("disabled", true);

            let formData = {
                _token: "{{ csrf_token() }}",
                //email: email,
                recording_url: recording_url,
                user_id: '{{ $user_id }}',
                session_id: '{{ $session_id }}',
            };

            $.ajax({
                url: "{{ route('session.recording') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    console.log('Submitted Form');

                    $("#responseMessage").html(
                        `<div class="alert alert-success">${response.message}</div>`
                    );
                    
                    $("#recordingForm")[0].reset();
                    $('#update_submit').prop("disabled", false);
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
