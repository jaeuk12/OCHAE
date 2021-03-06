<?php /* Template_ 2.2.3 2016/08/29 13:29:38 D:\Work\OCHAE\www\_template\content\member\my_question.html */
$TPL_rows_1=empty($TPL_VAR["rows"])||!is_array($TPL_VAR["rows"])?0:count($TPL_VAR["rows"]);?>
<script>
	$(document).ready(function(){
		$("table tbody tr.q td:not(:last-child)").click(function(){
			var next = $(this).parent().next();
			
			if(next.hasClass("show")){
				next.removeClass("show");
			}
			else{
				$("table tbody tr.a").removeClass("show");
				next.addClass("show");
			}
		});
		
		if($(".question_content table tbody tr").length >= <?php echo $TPL_VAR["list_num"]?>){
			$(".pagelist .more").click(function(){
				getMoreList();
			});
		}
		else{
			$(".pagelist .more").css("display", "none");
		}
	});
	
	function editQuestion(idx){
		if(typeof idx == "undefined"){
			idx = "";
		}
		
		if(!isProcess){
			modalProgress.show();
			
			$.ajax({
				type: 'POST',
				url: '/member/editQuestionFromAjax',
				data: {"idx" : idx},
				async: true,
				dataType: 'json',
				success: function(data) {
					modalProgress.close();
					
					if(data.code == 100){
						modalPop.show(data.content);
					}
					else{
						alert(data.message);
					}
				},
				error: function(xhr, status) {
					//alert('[' + status + ']\n\n' + xhr.responseText);
					modalProgress.close();
				}
			});
		}
	}
	
	function deleteAddress(idx){
		document.location = "/member/deleteQuestion?idx="+idx;
	}
	
	function getMoreList(){
		var questionSize = $(".question_content table tbody tr.q").length;
		
		$.ajax({
			type: 'POST',
			url: '/member/moreQuestionListAjax',
			data: {"size" : questionSize},
			async: true,
			dataType: 'json',
			success: function(data) {
				if(data.code == 100){
					var tbody = $(".question_content table tbody");
					
					$.each(data.list, function(k, v){
						var tr = "<tr class='q''><td>"+v.title+"</td>"	
								+"<td class='answer "+(v.hit == 0?"":v.answer==''?"Y":"A")+"'>"+(v.hit == 0?"미확인":v.answer=''?"처리 중입니다":"답변완료")+"</td>"
								+"<td class='regdate'>"+v.reg_date+"</td>"
								+"<td>"+(v.hit == 0?"<button onclick='editQuestion("+v.idx+")'>EDIT</button>":"")+"</td></tr>"
								+"<tr class='a'><td colspan='4'>"
								+"<h5>QUESTION</h5>"
								+"<div class='question'>"+v.content+"</div>"
								+"<h5>ANSWER</h5>"
								+"<div>"+(v.hit == 0?"미확인 상태입니다":v.answer == ''?"처리중입니다":v.answer)+"</div></td></tr>";
								
						tbody.append(tr);
					});
					
					$("table tbody tr.q td:not(:last-child)").off("click").click(function(){
						var next = $(this).parent().next();
			
						if(next.css("display") == "none"){
							$("table tbody tr.a").css("display", "none");
							next.show();
						}
						else{
							next.hide();
						}
					});
					
					$(".pagelist .more .more_num").html(number_format(questionSize+data.list.length));
					
					if(data.list.length < <?php echo $TPL_VAR["list_num"]?>){
						$(".pagelist .more").remove();
					}
				}
				else if(data.code == 200){
					$(".pagelist .more").remove();
				}
			},
			error: function(xhr, status) {
				alert('[' + status + ']\n\n' + xhr.responseText);
			}
		});
	}
</script>

<article class="my_account">
	<?php echo $TPL_VAR["my_account_left"]?>

	<div class="right_container">
		<div class="question_content">
			<h3>QUESTION<a href="javascript:editQuestion();">WRITE</a></h3>
			<table>
				<thead>
					<tr>
						<th>제목</th>
						<th class="answer">답변 상태</th>
						<th class="regdate">질문 등록일</th>
						<th class="del"></th>
					</tr>
				</thead>
				<tbody>
<?php if($TPL_rows_1){foreach($TPL_VAR["rows"] as $TPL_V1){?>
					<tr class="q">
						<td><?php echo $TPL_V1["title"]?></td>
						<td class="answer <?php if($TPL_V1["hit"]==0&&$TPL_V1["answer"]==''){?>n<?php }elseif($TPL_V1["answer"]==""){?>Y<?php }else{?>A<?php }?>"><?php if($TPL_V1["hit"]==0&&$TPL_V1["answer"]==''){?>미확인<?php }elseif($TPL_V1["answer"]==""){?>처리 중입니다<?php }else{?>답변완료<?php }?></td>
						<td class="regdate"><?php echo $TPL_V1["reg_date"]?></td>
						<td><?php if($TPL_V1["hit"]==0&&$TPL_V1["answer"]==''){?><button onclick="editQuestion(<?php echo $TPL_V1["idx"]?>)">EDIT</button><?php }?></td>
					</tr>
					<tr class="a">
						<td colspan="4">
							<div>
								<div>
									<h5>QUESTION</h5>
									<div class="question"><?php echo $TPL_V1["content"]?></div>
									<h5>ANSWER</h5>
									<div><?php if($TPL_V1["hit"]==0&&$TPL_V1["answer"]==''){?>미확인 상태입니다<?php }elseif($TPL_V1["answer"]==""){?>처리 중입니다<?php }else{?><?php echo $TPL_V1["answer"]?><?php }?></div>
								</div>
							</div>
						</td>
					</tr>
<?php }}?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="pagelist">
							<?php echo $TPL_VAR["pagelist"]?>

							<button class="more"><img src="/image/icon/icon_triangle_down.png"/>MORE<span class="more_num"><?php echo number_format($TPL_VAR["last"])?></span> of <?php echo number_format($TPL_VAR["total"])?></button>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</article>