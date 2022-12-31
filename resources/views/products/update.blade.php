@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.6/tagify.css" integrity="sha512-Ft73YZFLhxI8baaoTdSPN8jKRPhYu441A8pqlqf/CvGkUOaLCLm59ZWMdls8lMBPjs1OZ31Vt3cmZsdBa3EnMw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
<section>
    <div class="container-fluid">
    	<div class="page-header">
            <div class="d-inline">
                @if (Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{Session::get('error')}}
                        <button title="Close Button" type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <form enctype="multipart/form-data" action="{{ route('product.update',  $product->id) }}" method="POST">
        @csrf
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Product Name<span class="text-danger">*</span></label>
                            <input type="text" id="title" name="title" value="{{ $product->title }}"class="form-control @error('title') is-invalid @enderror" placeholder="Product name" required>

                            @error('title')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="sku">Product Sku<span class="text-danger">*</span></label>
                            <input type="text" id="sku" name="sku" value="{{ $product->sku }}"class="form-control @error('sku') is-invalid @enderror" placeholder="Product Sku" required>

                            @error('sku')
                            <span class="text-danger" role="alert">
                                <p>{{ $message }}</p>
                            </span>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="description" cols="30" rows="4" class="form-control">{{ $product->description }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Media</h6>
                </div>
                <div class="card-body border">
                    {{-- <input type="file" name="product_image" id="product_image" data-height="105" @if ($product) data-default-file="{{ asset('img/' . $product->images->file_path) }}" @endif class="dropify form-control @error('product_image') is-invalid @enderror"> --}}

                     <input type="file" name="product_image" id="product_image" data-height="105" class="dropify form-control @error('product_image') is-invalid @enderror">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Variants</h6>
                </div>
                <div class="card-body">
                    <div class="row row_del">
                        @foreach($product->variants as $key => $variant)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Option</label>
                                <select name="variant_id[]" id="variant_id" class="form-control variant_id">
                                    <option value=""> Select Variant</option>
                                        @foreach ($variantDatas as $key => $variantData)
                                            <option value="{{ $variantData->id }}" @if($variantData->id == $product->id) selected @endif> {{ $variantData->title }} </option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="float-right text-primary" style="cursor: pointer; display:none">Remove</label>
                                <label for="">.</label>
                                <input name='basic' class="form-control" value="{{ $variant->pivot->variant }}" autofocus>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div  id="AddField">

                    </div>

                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-primary" id="add" >Add another option</button>
                </div>

                <div class="card-header text-uppercase">Preview</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>Variant</td>
                                <td>Price</td>
                                <td>Stock</td>
                            </tr>
                            </thead>
                            <tbody id="v_input">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-lg btn-primary">Update</button>
    <button type="button" class="btn btn-secondary btn-lg">Cancel</button>
    </form>


</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.6/tagify.min.js" integrity="sha512-AmE3EVNNwtyQjoNntro+pDzpsVBxNKVYT61NttiVHYB9XXxhQupbjt6VPRIdt4DO4CZKXIBWE8fn+/ivq+et5A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script>

    $('.dropify').dropify();

</script>

<script>
    $( document ).ready(function() {
    //field append
    var i = 1;
    $("#add").click(function () {
        if( i < 3 ){
        ++i;
        $("#AddField").append(`<div class="row" id="removed">
            <div class="col-md-4">
                <div class="form-group">
                <label for="">Option</label>
                <select name="variant_id[]" id="variant_id" class="form-control variant_id">
                    @foreach ($variantDatas as $key => $variant)
                        <option value="{{ $variant->id }}" > {{ $variant->title }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label class="float-right text-primary btn_remove"
                            style="cursor: pointer;" id="del" >Remove</label>
                    <label for="">.</label>
                    <input name="basic" class="form-control">
                </div>
            </div>
            </div>`);
        }else{
            alert("You can not add more than 3 options");
        }
    });

    });

    $(document).on('click', '.btn_remove', function() {
        $(this).parents('#removed').remove();
        i--;
    });

    $(document).on('click', 'select.variant_id', function () {
        $('select[name*="variant_id[]"] option').attr('disabled',false);
        $('select[name*="variant_id[]"]').each(function(){
            var $this = $(this);
            $('select[name*="variant_id[]"]').not($this).find('option').each(function(){
                if($(this).attr('value') == $this.val())
                $(this).attr('disabled',true);
            });
        });
        });

</script>

<script>
    var input = document.querySelector('input[name=basic]'),
    tagify = new Tagify(input, {
        pattern             : /^.{0,20}$/,
        delimiters          : ",| ",
        keepInvalidTags     : true,

        editTags            : {
            clicks: 2,
            keepInvalid: false
        },
        maxTags             : 3,
        blacklist           : ["foo", "bar", "baz"],
        whitelist           : ["temple","stun","detective","sign","passion","routine","deck","discriminate","relaxation","fraud","attractive","soft","forecast","point","thank","stage","eliminate","effective","flood","passive","skilled","separation","contact","compromise","reality","district","nationalist","leg","porter","conviction","worker","vegetable","commerce","conception","particle","honor","stick","tail","pumpkin","core","mouse","egg","population","unique","behavior","onion","disaster","cute","pipe","sock","dialect","horse","swear","owner","cope","global","improvement","artist","shed","constant","bond","brink","shower","spot","inject","bowel","homosexual","trust","exclude","tough","sickness","prevalence","sister","resolution","cattle","cultural","innocent","burial","bundle","thaw","respectable","thirsty","exposure","team","creed","facade","calendar","filter","utter","dominate","predator","discover","theorist","hospitality","damage","woman","rub","crop","unpleasant","halt","inch","birthday","lack","throne","maximum","pause","digress","fossil","policy","instrument","trunk","frame","measure","hall","support","convenience","house","partnership","inspector","looting","ranch","asset","rally","explicit","leak","monarch","ethics","applied","aviation","dentist","great","ethnic","sodium","truth","constellation","lease","guide","break","conclusion","button","recording","horizon","council","paradox","bride","weigh","like","noble","transition","accumulation","arrow","stitch","academy","glimpse","case","researcher","constitutional","notion","bathroom","revolutionary","soldier","vehicle","betray","gear","pan","quarter","embarrassment","golf","shark","constitution","club","college","duty","eaux","know","collection","burst","fun","animal","expectation","persist","insure","tick","account","initiative","tourist","member","example","plant","river","ratio","view","coast","latest","invite","help","falsify","allocation","degree","feel","resort","means","excuse","injury","pupil","shaft","allow","ton","tube","dress","speaker","double","theater","opposed","holiday","screw","cutting","picture","laborer","conservation","kneel","miracle","brand","nomination","characteristic","referral","carbon","valley","hot","climb","wrestle","motorist","update","loot","mosquito","delivery","eagle","guideline","hurt","feedback","finish","traffic","competence","serve","archive","feeling","hope","seal","ear","oven","vote","ballot","study","negative","declaration","particular","pattern","suburb","intervention","brake","frequency","drink","affair","contemporary","prince","dry","mole","lazy","undermine","radio","legislation","circumstance","bear","left","pony","industry","mastermind","criticism","sheep","failure","chain","depressed","launch","script","green","weave","please","surprise","doctor","revive","banquet","belong","correction","door","image","integrity","intermediate","sense","formal","cane","gloom","toast","pension","exception","prey","random","nose","predict","needle","satisfaction","establish","fit","vigorous","urgency","X-ray","equinox","variety","proclaim","conceive","bulb","vegetarian","available","stake","publicity","strikebreaker","portrait","sink","frog","ruin","studio","match","electron","captain","channel","navy","set","recommend","appoint","liberal","missile","sample","result","poor","efflux","glance","timetable","advertise","personality","aunt","dog"],
        transformTag        : transformTag,
        backspace           : "edit",
        placeholder         : "Type something",
        dropdown : {
            enabled: 1,
            fuzzySearch: false,
            position: 'text',
            caseSensitive: true,
        },
        templates: {
            dropdownItemNoMatch: function(data) {
                return `<div class='${this.settings.classNames.dropdownItem}' value="noMatch" tabindex="0" role="option">
                    No suggestion found for: <strong>${data.value}</strong>
                </div>`
            }
        }
    })

    function getRandomColor(){
        function rand(min, max) {
            return min + Math.random() * (max - min);
        }

        var h = rand(1, 360)|0,
            s = rand(40, 70)|0,
            l = rand(65, 72)|0;

        return 'hsl(' + h + ',' + s + '%,' + l + '%)';
    }

    function transformTag( tagData ){
        tagData.color = getRandomColor();
        tagData.style = "--tag-bg:" + tagData.color;

        if( tagData.value.toLowerCase() == 'shit' )
            tagData.value = 's✲✲t'
    }

    tagify.on('add', function(e){

        let TagName = e.detail.data.value;
        $("#v_input").append(`<tr for="variant_price in product_variant_prices">
            <td>${TagName}</td>
            <td> <input type="hidden" name="variant[]" value="${TagName}" ></td>
            <td>
                <input type="text" class="form-control" name="price[]">
            </td>
            <td>
                <input type="text" class="form-control" name="stock[]">
            </td>
        </tr>`);
    })

    tagify.on('invalid', function(e){
        $('input').off('add remove').on('maxTagsExceed', function(e) {
    alert('Maximum number of tags reached!');
    });
        console.log(e, e.detail);
    })

    var clickDebounce;

    tagify.on('click', function(e){
        const {tag:tagElm, data:tagData} = e.detail;
        clearTimeout(clickDebounce);
        clickDebounce = setTimeout(() => {
            tagData.color = getRandomColor();
            tagData.style = "--tag-bg:" + tagData.color;
            tagify.replaceTag(tagElm, tagData);
        }, 200);
    })

    tagify.on('dblclick', function(e){
        clearTimeout(clickDebounce);
    })

</script>
@endsection
