<?php 
use App\Models\BedOrder;
use App\Models\Room;
use App\Models\Branch;
use App\Models\Zone;
use App\Models\AdminUser;
?>
@foreach ($rooms as $room)
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Phòng: {{$room->name}}</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="box-body">
        @foreach($room->beds as $bed)
            <?php 

            ?>
            @if($bed->status == -1)
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-calendar-minus-o"></i></span>
                        <div class="info-box-content">
                          <span class="info-box-text">Phòng: {{$room->name}}</span>
                          <span class="info-box-number">Giường: {{$bed->name}}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 0%">
                                </div>
                            </div>
                            <span class="progress-description">
                            Trạng thái: Khoá
                            </span>
                        </div>

                        <button type="button" class="btn btn-success" 
                            data-bedid="{{$bed->id}}" data-toggle="modal" data-target="#unlockModal" style="margin-top: 15px;">
                            <i class="fa fa-unlock"></i> Mở khoá
                        </button>
                    </div>
                </div>
            @else
                <?php
                    $order = BedOrder::where('bed_id', $bed->id)->where('status', '<>', 2)->first();
                ?>
                @if(is_null($order))
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-calendar-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Phòng: {{$room->name}}</span>
                            <span class="info-box-number">Giường: {{$bed->name}}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%">
                        </div>
                            </div>
                            <span class="progress-description">
                            Trạng thái: Trống
                            </span>
                        </div>
                        <button type="button" class="btn btn-danger" 
                            data-bedid="{{$bed->id}}" data-toggle="modal" data-target="#lockModal" style="margin-top: 15px;">
                            <i class="fa fa-unlock"></i> Khoá
                        </button>
                        <button type="button" class="btn btn-success btn-select-bed"
                            data-bedid="{{$bed->id}}" style="margin-top: 15px;">
                            <i class="fa fa-unlock"></i> Chọn giường
                        </button>
                    </div>
                </div>
                @else
                    @if($order->status == 1)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="info-box bg-red">
                            <span class="info-box-icon"><i class="fa fa-calendar-minus-o"></i></span>
                            <div class="info-box-content">
                            <span class="info-box-text">Phòng: {{$room->name}}</span>
                            <span class="info-box-number">Giường: {{$bed->name}}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 0%">
                                    </div>
                                </div>
                                <span class="progress-description">
                                Trạng thái: Khoá
                                </span>
                                <button type="button" class="btn btn-danger" 
                                data-bedid="{{$bed->id}}" data-toggle="modal" data-target="#lockModal" style="margin-top: 15px;">
                                <i class="fa fa-unlock"></i> Khoá
                                </button>
                            </div>
                        </div>
                    </div>
                    @elseif($order->status == 2)
                    <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-calendar-check-o"></i></span>
                    <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">41,410</span>
                    <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                    70% Increase in 30 Days
                    </span>
                    </div>
                    </div>
                    </div>
                    @else
                    <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-calendar-minus-o"></i></span>
                    <div class="info-box-content">
                    <span class="info-box-text">Events</span>
                    <span class="info-box-number">41,410</span>
                    <div class="progress">
                    <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                    70% Increase in 30 Days
                    </span>
                    </div>
                    </div>
                    </div>
                    @endif
                @endif
            @endif
        @endforeach
        </div>
    </div>
    
@endforeach
<div class="modal fade" id="unlockModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> Mở khoá giường</h4>
      </div>
      <div class="modal-body">
        <form class="tagForm" id="tag-form"  >
          <div class="form-group">
            <label for="bed-name" class="control-label">Mở khoá giường</label>
            <input type="hidden" class="form-control bed-id" id="bed-id"/>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="tag-form-submit" class="tag-form-submit btn btn-primary">Đồng ý</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="lockModal" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" >Khoá giường</h4>
      </div>
      <div class="modal-body">
        <form class="tagForm" id="tag-form"  >
          <div class="form-group">
            <label for="bed-name" class="control-label">Khoá giường</label>
            <input type="hidden" class="form-control bed-id" id="bed-id"/>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="tag-form-submit" class="tag-form-submit btn btn-primary">Đồng ý</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="bedSelect" tabindex="-1" role="dialog" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" >Chọn giường</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="tag-form-submit" class="tag-form-submit btn btn-primary">Đồng ý</button>
      </div>
    </div>
  </div>
</div>