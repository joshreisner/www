<div class="modal fade" id="contact">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Contact</h3>
			</div>
			{!! Form::open(['method'=>'post']) !!}
				<div class="modal-body">
					<div class="form-group">
						<input type="email" name="email" class="form-control" placeholder="Email Address">
					</div>
					<div class="form-group">
						<textarea name="message" class="form-control" placeholder="Message"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Send Message</button>
				</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>