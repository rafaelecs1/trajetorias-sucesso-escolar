#!/bin/bash
# array with years of trajetorias
array1=( 2015 2016 2017 2018 2019 )
for a in "${array1[@]}"
do
	# array with states id
	array2=( 27 28 29 31 32 33 35 41 42 43 50 51 52 53 )
	for b in "${array2[@]}"
	do
		curl http://localhost/painel/estado/$b/$a/
	done
	
done
echo "FIM"