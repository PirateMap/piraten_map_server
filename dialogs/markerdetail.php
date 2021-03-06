<script id="markerTemplate" type="text/x-jquery-tmpl">
<![CDATA[
	<div class="modal" style="position: relative; top: auto; left: auto; margin: 0 auto; z-index: 9001">
		<div class="modal-header">
			<h3>{{:tb}}</h3>
			<a href="#" onclick="javascript:closeModal();" class="close">&#215;</a>
		</div>
		<div class="modal-body">
			<form>
				<fieldset>
					{{if i}}
						<div class="clearfix">
							<label><?php echo _('Image'); ?></label>
							<div class="input">
								<a target="_blank" href="{{:i}}"><img class="photo" src="{{:i}}" /></a>
							</div>
						</div>
					{{/if}}
					{{if loginOK}}
						<div class="clearfix">
							<label for="typ[{{:id}}]"><?php echo _('Marker'); ?></label>
							<div class="input">
								<select class="xlarge" id="typ[{{:id}}]" name="typ[{{:id}}]">
								{{fields posterFlags}}
									{{if value!=''}}
										{{if key==#parent.data.t}}
											<option value="{{:key}}" selected="selected">{{:value}}</option>
										{{else}}
											<option value="{{:key}}">{{:value}}</option>
										{{/if}}
									{{/if}}
								{{/fields}}
								</select>
							</div>
						</div>
						<div class="clearfix">
							<label for="city[{{:id}}]"><?php echo _('City'); ?></label>
							<div class="input">
								<input type="text" size="30" class="xlarge" name="city[{{:id}}]" id="city[{{:id}}]" value="{{:ci}}" />
							</div>
						</div>
						<div class="clearfix">
							<label for="street[{{:id}}]"><?php echo _('Street'); ?></label>
							<div class="input">
								<input type="text" size="30" class="xlarge" name="street[{{:id}}]" id="street[{{:id}}]" value="{{:s}}" />
							</div>
						</div>
					{{/if}}
					<div class="clearfix">
						<label for="comment[{{:id}}]"><?php echo _('Description'); ?></label>
						<div class="input">
							<textarea rows="3" cols="30" class="xlarge" name="comment[{{:id}}]" id="comment[{{:id}}]">{{:c}}</textarea>
						</div>
					</div>
					{{if loginOK}}
						<div class="clearfix">
							<label for="image[{{:id}}]"><?php echo _('Image URL'); ?></label>
							<div class="input">
								<input type="text" size="30" class="xlarge" name="image[{{:id}}]" id="image[{{:id}}]" value="{{:i}}" />
							</div>
						</div>
					{{/if}}
					<div class="clearfix">
						<div class="input">
							<small><?php echo _f('Last changed by %1$s<br />on %2$s', '<b>{{:u}}</b>', '<b>{{:d}}</b>'); ?></small>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		{{if loginOK}}
		<div class="modal-footer">
			<input type="button" value="<?php echo _('Save'); ?>" class="btn primary" onclick="javascript:change({{:id}})" />
			<input type="button" value="<?php echo _('Delete'); ?>" class="btn danger" onclick="javascript:delid({{:id}})" />
		</div>
		{{/if}}
	</div>
]]>
</script>