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
	

		$BrandMasks = [];
		
		foreach($records as $recKey => $rec){
            
		    
		    if( $rec["NODETYPE"] == 0 && $rec["DATEINSERTED"] < date('Y-m-d', strtotime('-1 year'))){
		        
		        unset($records[$recKey]); 
		    }
		}
		
		$uniqueBrandMasks = array_unique($BrandMasks);
			
		$brandsFilter = [];
		
		foreach($records as &$value){
		    
		    $value["BRANDMASK"] = $this->filterBrands($value["BRANDMASK"]); 
		}
		
		
		$brandMasks = ['1'=> '77', '2' => '83', '4' => '70', '8' => '00', '16' => '66', '32' => '55', '64' => '56', '128' => '57', '256' => '58'];
				
		$filteredAndChangedBrands = [];
		
		foreach($brandMasks as $key => $val){
		    
		    if(in_array($val, $_SESSION["SS_ALLBRANDS"]["CODBRANDS"])){
		        
		        $filteredAndChangedBrands[] = $key;
		    }
		}
		

		
		$arrayInteresction = [];
		
		foreach($records as $key => &$val){ 

		    if(is_array($val["BRANDMASK"])){
		        
		        $arrayInteresction = array_intersect($val["BRANDMASK"][0], $filteredAndChangedBrands);
		        
		        if(empty($arrayInteresction)){
	
		            unset($records[$key]); 
		        } 
		    }else{ 
		        
		        if(!in_array($val["BRANDMASK"], $filteredAndChangedBrands)){
	
		            unset($records[$key]);
		        } 
		    }
		}

}
