#!/bin/bash
# array with years of trajetorias
array1=( 2015 2016 2017 2018 2019 )
for a in "${array1[@]}"
do
	# array with states id
	array2=( 11 12 13 14 15 16 17 21 22 23 24 25 26 )
	for b in "${array2[@]}"
	do
		curl http://localhost/painel/estado/$b/$a/
	done
	
done
echo "FIM"