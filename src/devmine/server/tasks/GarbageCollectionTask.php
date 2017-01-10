<?php



namespace devmine\server\tasks;

class GarbageCollectionTask extends AsyncTask{

	public function onRun(){
		gc_enable();
		gc_collect_cycles();
	}
}
