@extends ('admin.client.client_app')
@section('content')
    <style>
        td {
            text-align: center;
            vertical-align: middle !important;
        }
    </style>
    <section class="assets_list">
        <div class="row bg-white">
            <div class="col-12 py-3">
                <button class="btn btn-success" onclick="load_information()"> Back </button>
            </div>
            <div class="col-12 d-flex">
                <h4>{{__("remediation_asset_name")}}</h5> <b> : @if(session('locale') == 'fr') {{ $asset->name }} @else {{ $asset->name }} </b> @endif
            </div>
            <div class="col-12 d-flex">
                <h4>{{__("remediation_asset_business_unit")}}</h4><b> : @if(session('locale') == 'fr') {{ $asset->business_unit }} @else {{ $asset->business_unit }} </b> @endif
            </div>

            <div class="col-12 overflow-auto py-3">
                <table class="table" id="remediation_" style="min-width: 800px">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Control Question</th>
                            <th>User Response</th>
                            <th>Review Comment</th>
                            <th style="min-width: 200px">Person In Charge</th>
                            <th style="min-width: 350px">Proposed Remediation</th>
                            <th>Initial Rating</th>
                        </tr>
                    </thead>
                    <tbody id="render_questions"></tbody>
                </table>
            </div>

            <div class="col-12 p-3 d-flex justify-content-end">
                <button class="btn btn-primary" onclick="add_new_remediation_db()"> Add </button>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        let questions;
        let options;
        function load_information() {
            $.ajax({
                url: '/audit/remediation/controls/'+window.location.pathname.split("/").pop(),
                type:'GET',
                success: function(res){
                    questions   = res.questions;
                    users       = res.users;
                    render_data();
                }
            });
        }
        load_information();

        function render_data(){
            $('#render_questions').html("");
            options = "";
            users.map((user, index)=>{
                options += `<option value="${user.id}">${user.name}</option>`;
            });

            questions.map((question, index)=>{
                console.log("question", question);
                $('#render_questions').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${question.question_short}</td>
                        <td>${question.question_response}</td>
                        <td>${question.admin_comment}</td>
                        <td><select class="form-control" name="remediation_user_id" index="${index}" onchange="add_current_val(event)">${options}<select></td>
                        <td><textarea rows="3" class="form-control" name="proposed_remediation" index="${index}" onchange="add_current_val(event)">${question.proposed_remediation}</textarea></td>
                        <td>${question.rating}</td>
                    </tr>
                `);
            });
        }

        function add_current_val(event){
            let index = $(event.target).attr("index");
            let name  = $(event.target).attr("name");
            let value = $(event.target).val();

            questions[index][name] = value;
            console.log("questions 12345", questions);
        }

        function add_new_remediation_db(event) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            console.log("questions", questions);
            $.ajax({
                url: '{{route("add_new_remediation_db")}}',
                data: {
                    'questions':questions
                },
                dataType:'json',
                type    :'post',
                success : function (res) {
                    if (!res.status) {
                        swal('', res.error, 'warning');
                    }else{
                        swal('', res.message, 'success');
                        setTimeout(() => {
                            location.href = '/audit/completed';
                        }, 500);
                    }
                    
                }
            });
        }

    </script>
@endpush