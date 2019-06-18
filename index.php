<?php

class UniqueCombSum{

  	public function getCombinations($base,$n){
			    
			    $baselen = count($base);
			    if($baselen == 0){
			        return;
			    }
			    if($n == 1){
			        $return = array();
			        foreach($base as $b){
			            $return[] = array($b);
			        }
			        return $return;
			    }else{
			        //get one level lower combinations
			        $oneLevelLower = $this->getCombinations($base,$n-1);
			        
			        //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
			        $newCombs = array();
			        
			        foreach($oneLevelLower as $oll){
			            
			            $lastEl = $oll[$n-2];
			            $found = false;
			            foreach($base as  $key => $b){
			                if($b == $lastEl){
			                    $found = true;
			                    continue;
			                    //last element found
			                    
			                }
			                if($found == true){
			                    //add to combinations with last element
			                    if($key < $baselen){
			                        
			                        $tmp = $oll;
			                        $newCombination = array_slice($tmp,0);
			                        $newCombination[]=$b;
			                        $newCombs[] = array_slice($newCombination,0);
			                    }
			                    
			                }
			            }
			            
			        }
			    }
			    
			    return $newCombs;	    
	}
	
	public function filterBrands($num){
	    //
	    
	    $arr = [1,2,4,8,16,32,64,128,256];
	    
	    $indexNum = 0;
	    
	    $brandFilters = [];
	    
	    $numOfCombinations = 1;
	    
	     
	    if(in_array($num, $arr)){
	        
	        return $brandFilters[] = (int)($num);
	    }else{
	        
	        return $this->findFilter($num, $numOfCombinations+1);
	    }
	    
	}
	
	
	public function findFilter($num, $numOfCombinations){
	    
	    if($numOfCombinations == 10){
	        return 'Brand Not found';
	    }
	    
	    $uniqueCombinations = $this->getCombinations(array(1,2,4,8,16,32,64,128,256),$numOfCombinations);
	    
	    
	    //var_dump('usao');die;
	    foreach($uniqueCombinations as $val){
	        if(array_sum($val) == $num){
	            
	            $brandFilters[] = $val;
	        }
	    }
	    if(empty($brandFilters)){
	        
	        return $this->findFilter($num, $numOfCombinations+1);
	    }
	    return $brandFilters;
	}
	
	
	public function getTreeXML($req)
	{	
	
         $marketFilter =  $req['P_CODMARKET']; //mercato del filtro
	     
	    foreach($this->appsDocumentale as $app)
	    {	    
	        if ($app['CODCOMAPPL'] == $req['P_COMAPPLPTLAPPL'])
	        {
	            if (isset($_SESSION["INTERNAL_APPL"]))
	            {
	                foreach($_SESSION["INTERNAL_APPL"] as $key => $value)
	                {
	                    if (isset($value["MARKETS"]))
	                    {
	                        if (in_array($marketFilter, $value["MARKETS"]))
	                        {
	                          if ($value["APPLNAME"] == $app['APPLICATION'])
	                          {
	                              //in sessione ho un ruolo per l'applicazione del filtro
	                             // echo $value["APPLNAME"] . ' - ' . $value["HIERARCHY"] . ' - ' . $value["ROLETYPE"];
	                             $req['CODPROFILE'] = $value["HIERARCHY"];
	                             $req['ROLETYPE'] = $value["ROLETYPE"];
	                          } 
	                            
	                        }
	                    }
	                }
	            }
	        }
	    }
	    
	    
	    
		$records = $this->DBWrapInst->getTreeData($req);
		$BrandMasks = [];
		//var_dump($records);die;
		foreach($records as $recKey => $rec){
            
		    
		    //$BrandMasks[] = $rec["BRANDMASK"];
		    if( $rec["NODETYPE"] == 0 && $rec["DATEINSERTED"] < date('Y-m-d', strtotime('-1 year'))){
		        
		        unset($records[$recKey]); 
		    }
		}
		
		$uniqueBrandMasks = array_unique($BrandMasks);
			
		$brandsFilter = [];
		
		foreach($records as &$value){
		    
		    $value["BRANDMASK"] = $this->filterBrands($value["BRANDMASK"]); 
		}
		
		
		//var_dump($records);die;
		
		$brandMasks = ['1'=> '77', '2' => '83', '4' => '70', '8' => '00', '16' => '66', '32' => '55', '64' => '56', '128' => '57', '256' => '58'];
				
		$filteredAndChangedBrands = [];
		
		foreach($brandMasks as $key => $val){
		    
		    if(in_array($val, $_SESSION["SS_ALLBRANDS"]["CODBRANDS"])){
		        
		        $filteredAndChangedBrands[] = $key;
		    }
		}
		
		//var_dump($filteredAndChangedBrands);die;
		
		$arrayInteresction = [];
		
		foreach($records as $key => &$val){ 

		    if(is_array($val["BRANDMASK"])){
		        
		        $arrayInteresction = array_intersect($val["BRANDMASK"][0], $filteredAndChangedBrands);
		        
		        if(empty($arrayInteresction)){
		            //var_dump('empty:',$key);
		            unset($records[$key]); 
		        } 
		    }else{ 
		        
		        if(!in_array($val["BRANDMASK"], $filteredAndChangedBrands)){
		            //var_dump('KEY2:', $val["BRANDMASK"]);
		            unset($records[$key]);
		        } 
		    }
		}

}
