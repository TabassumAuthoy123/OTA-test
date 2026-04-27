@extends('master')
@section('header_css')
<style>
.b2c-page-header{background:linear-gradient(135deg,#1a5276,#2471a3);color:#fff;padding:16px 24px;border-radius:8px 8px 0 0;display:flex;justify-content:space-between;align-items:center;}
.b2c-page-header h5{margin:0;font-size:18px;font-weight:700;}
.b2c-table th{background:#1a5276;color:#fff;font-size:13px;padding:10px 12px;white-space:nowrap;}
.b2c-table td{font-size:13px;padding:9px 12px;vertical-align:middle;}
.b2c-table tr:hover td{background:#eaf4ff;}
.btn-add{background:#f0a500;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:700;text-decoration:none;}
.btn-edit{background:#f0a500;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:12px;font-weight:600;}
.btn-del{background:#dc3545;color:#fff;border:none;padding:4px 10px;border-radius:5px;font-size:12px;font-weight:600;}
.yt-logo{max-width:60px;max-height:35px;border-radius:4px;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <div class="card" style="border-radius:8px;overflow:hidden;">
    <div class="b2c-page-header">
      <div><h5>YouTube Links</h5><small>Dashboard &rsaquo; Configuration &rsaquo; Youtube-links</small><br>
        <form method="GET" action="{{ url('b2c/config/youtube-links') }}" class="mt-2 d-inline-flex gap-2">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." style="width:180px;font-size:13px;padding:4px 8px;border:1px solid #ccc;border-radius:5px;">
          <button class="btn btn-light btn-sm"><i class="fas fa-search"></i></button>
        </form>
      </div>
      <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">+ Add YouTube Link</button>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered mb-0 b2c-table">
          <thead><tr><th>SL</th><th>Logo</th><th>Name</th><th>Link</th><th>Created</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($items as $i => $item)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>@if($item->logo && file_exists(public_path($item->logo)))<img src="{{ url($item->logo) }}" class="yt-logo">@else-@endif</td>
              <td>{{ $item->name }}</td>
              <td><a href="{{ $item->link }}" target="_blank" style="color:#1a5276;">{{ $item->link }}</a></td>
              <td>{{ $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : 'N/A' }}</td>
              <td>
                <button class="btn-edit" onclick="openEdit({{ $item->id }},'{{ addslashes($item->name) }}','{{ addslashes($item->link) }}')">Edit</button>
                <a href="{{ url('b2c/config/youtube-links/'.$item->id.'/delete') }}" class="btn-del" onclick="return confirm('Delete?')">Delete</a>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-5 text-muted">No YouTube links.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div></div>

<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog"><form method="POST" action="{{ url('b2c/config/youtube-links/store') }}" enctype="multipart/form-data">@csrf
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add YouTube Link</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold text-danger">* Name</label><input type="text" name="name" class="form-control" placeholder="YouTube Channel" required></div>
        <div class="mb-3"><label class="form-label fw-bold text-danger">* Link</label><input type="url" name="link" class="form-control" placeholder="https://www.youtube.com/yourchannel" required></div>
        <div class="mb-3"><label class="form-label fw-bold">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-warning text-white fw-bold">Submit</button></div>
    </div>
  </form></div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog"><form id="editForm" method="POST" enctype="multipart/form-data">@csrf
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit YouTube Link</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-bold text-danger">* Name</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-bold text-danger">* Link</label><input type="url" name="link" id="edit_link" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-bold">Logo</label><input type="file" name="logo" class="form-control" accept="image/*"></div>
      </div>
      <div class="modal-footer"><button type="submit" class="btn btn-warning text-white fw-bold">Update</button></div>
    </div>
  </form></div>
</div>
@endsection
@section('footer_js')
<script>
function openEdit(id,name,link){
  document.getElementById('editForm').action='/b2c/config/youtube-links/'+id+'/update';
  document.getElementById('edit_name').value=name;
  document.getElementById('edit_link').value=link;
  new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endsection
