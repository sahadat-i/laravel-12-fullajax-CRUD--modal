@extends('layouts.app')

@section('content')
<h1>Welcome to the Students Page</h1>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Tambah Data
</button>

@if (session('success'))
<div class="alert alert-success mt-3 collapse show" role="alert" id="success-alert">
  {{ session('success') }}
</div>
@endif
<table class="table" id="datatable">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Lecture Name</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>

  </tbody>
</table>
<!-- Modal Tambah -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm">
          @csrf
          <div class="mb-3">
            <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Input Lecture" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="tambahlecture">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="exampleModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editStudentForm">
          @csrf
          <div class="mb-3">
            <input type="text" class="form-control" id="edit_student_name" name="student_name" placeholder="Input Lecture" required>
            <input type="hidden" class="form-control" id="studentId" name="studentId">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="editlecture" >Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal Edit -->
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
      $('#datatable').DataTable({
          ajax: {
            url: '{{ route('students.json') }}',
              // dataSrc: function(json) {
              //   console.log('🧾 Server Response:', json);
              //   return json.data;
              // },
              // error: function(xhr) {
              //   console.error('❌ Error dari server:', xhr.responseText);
              // }
            },
          columns: [
              {
                data: null, // 👈 penting: data null agar bisa override
                name: 'index',
                render: function (data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false,
                searchable: false
            },
            { data: 'student_name', name: 'student_name' },
            {data:'actions', name: 'actions',
              orderable: false, searchable: false, render: function(data, type, row) {
                return `
                          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalEdit" onclick="loadLectureData(${row.id})">Edit</button>
                          <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDelete(${row.id})">Delete</button>
                        `;
              }},
          ],
          processing: true,
          serverSide: true,
          searchDelay: 500,
        });
  });
  $('#tambahlecture').click(function(e) {
      e.preventDefault();
      var formData = $('#createForm').serialize();
      $.ajax({
          type: 'POST',
          url: '{{ route('students.store') }}',
          data: formData,
          success: function(response) {
              $('#exampleModal').modal('hide');
              $('.modal-backdrop').remove(); // ✅ bersihin sisa backdrop
              $('body').removeClass('modal-open'); // ✅ body bersih juga
              $('#datatable').DataTable().ajax.reload();
              $('#createForm')[0].reset();
              Swal.fire({
                title: 'Di Tambahkan',
                text: response.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })

          },
          error: function(xhr) {
            let res = xhr.responseJSON;
            let msg = res?.message || 'Something went wrong';
            console.error('❌ Error:', xhr.responseText);
            Swal.fire({
              title: 'Gagal Di Tambahkan',
              text: msg,
              icon: 'warning',
              confirmButtonText: 'Oke'
            })
          }
      });
  });
  // Load data untuk modal edit
  function loadLectureData(id) {
      fetch(`/students/edit/${id}`)
        .then(response => response.json())
        .then(data => {
          $('#edit_student_name').val(data.student_name).focus();
          $('#studentId').val(data.id);
          // Isi input lainnya sesuai data
        });
    }
  function confirmDelete(id) {
    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: "Data akan dihapus secara permanen!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // Buat dan submit form secara dinamis
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/students/${id}`;
        form.style.display = 'none';

        // Tambahkan CSRF token dan method spoofing
        form.innerHTML = `
          <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
          <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
      }
    });
    setTimeout(function () {
      $('#success-alert').slideUp(500); // slide hilang ke atas dalam 0.5 detik
    }, 2000);
  }
  // Submit form edit
  $('#editlecture').click(function(e) {
      e.preventDefault();
      var formData = $('#editStudentForm').serialize();
      $.ajax({
          type: 'PUT',
          url: '/students/' + $('#studentId').val(),
          data: formData,
          success: function(response) {
              $('#exampleModalEdit').modal('hide');
              $('.modal-backdrop').remove(); // ✅ bersihin sisa backdrop
              $('body').removeClass('modal-open'); // ✅ body bersih juga
              $('#datatable').DataTable().ajax.reload();
              $('#editStudentForm')[0].reset();
              Swal.fire({
                title: 'Berhasil Di Edit',
                text: response.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            })

          },
          error: function(xhr) {
            let res = xhr.responseJSON;
            let msg = res?.message || 'Something went wrong';
            console.error('❌ Error:', xhr.responseText);
            Swal.fire({
              title: 'Gagal Di Tambahkan',
              text: msg,
              icon: 'warning',
              confirmButtonText: 'Oke'
            })
          }
      });
  });
</script>
@endsection