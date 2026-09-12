<?php
$root = $argv[1] ?? '';
if (!$root || !is_file($root.'/config/db.php')) { throw new RuntimeException('Provide installed test site path.'); }
chdir($root); define('HIDDENCMS_CLI', TRUE); require 'index.php';
$db=HB()->db;$model=HB()->module('slider')->model();
function verify($value,$label) { if (!$value) throw new RuntimeException($label); echo "PASS $label\n"; }
$db->begin_transaction();
try {
    $file=$db->from('file')->row();
    if (!$file) throw new RuntimeException('An image in the test media library is required.');
    $first=$model->save(['title'=>'Test &amp; slider','effect'=>'fade','published'=>['1']]);
    $second=$model->save(['title'=>'Second slider','effect'=>'cube','published'=>['1']]);
    verify($model->get($first)['title']==='Test & slider','Slider created and decoded');
    $data=['image_id'=>$file['id'],'title'=>'Test &amp; slide','description'=>'Text &lt;script&gt;','position'=>2,'published'=>['1']];
    $slide=$model->save_slide($data,$first);$data['position']=1;$early=$model->save_slide($data,$first);
    verify((int)$model->slides($first)[0]['slide_id']===(int)$early,'Slide ordering');
    $model->sort_slides($first,[$slide,$early]);
    verify((int)$model->slides($first)[0]['slide_id']===(int)$slide && (int)$model->slides($first)[1]['position']===2,'Drag ordering persisted');
    foreach ([[$slide,$slide],[$slide],[$slide,999999],['bad',$early]] as $invalid) {
        try { $model->sort_slides($first,$invalid); verify(FALSE,'Invalid ordering rejected'); } catch (InvalidArgumentException $e) { verify(TRUE,'Invalid ordering rejected'); }
    }
    try { $model->sort_slides($second,[$slide,$early]); verify(FALSE,'Foreign slides rejected'); } catch (InvalidArgumentException $e) { verify(TRUE,'Foreign slides rejected'); }
    verify(!$model->slides($second),'Sliders isolated');
    $listed = array_values(array_filter($model->listing(),function($row) use ($first){ return (int)$row['slider_id']===(int)$first; }));
    verify(count($listed[0]['slides'])===2 && !empty($listed[0]['slides'][0]['image_path']),'Listing includes ordered image previews');
    $data['published']=[];$model->save_slide($data,$first,$slide);
    verify(count($model->slides($first,TRUE))===1,'Inactive slides excluded');
    $html=(string)HB()->widget('slider')->output(['slider_id'=>$first]);
    verify(strpos($html,'Test &amp; slide')!==FALSE && strpos($html,'&lt;script&gt;')!==FALSE,'Widget escapes content');
    $model->save(['title'=>'Hidden slider','effect'=>'fade','published'=>[]],$first);
    verify((string)HB()->widget('slider')->output(['slider_id'=>$first])==='','Inactive slider hidden');
    $data['button_label']='Go';$data['button_url']='javascript:alert(1)';
    try { $model->save_slide($data,$second); verify(FALSE,'Unsafe URL rejected'); } catch (InvalidArgumentException $e) { verify(TRUE,'Unsafe URL rejected'); }
    $model->delete($first);verify(!$model->slides($first),'Slider deletion cascades');
} finally { $db->rollback(); echo "Database changes rolled back.\n"; }
