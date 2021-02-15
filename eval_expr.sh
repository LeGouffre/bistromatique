#!/bin/bash

questions=(
	# "17+2.3*4/5|0123456789|+-*/%()|1||18.84" # ok
	# "{+ax.tc4d#|&{xt4#6+@9|abcdefg|1||{@.@4" # ok
	# "17 + 2.3 \* 4 / 5|0123456789|+-*/%()|1|spaces / no params" # ok with spaces
	# "{+ a x.t c 4 d #|&{xt4#6+89|abcdefg|1|spaces" # ok with spaces

	# "17++-++-2.3*4/5|0123456789|+-*/%()|1|multiple + -" # ok with multiple +
	# "{+aabaabx.tc4d#|&{xt4#6+89|abcdefg|1|multiple + -" # ok with multiple +

			### OK EXPR
		## addition
	# "1+1|0123456789|+-*/%()|1||2"
	# "1+1|1|+-*/%()|1|Base unaire|1"
	# "1+1|01|+-*/%()|1|JCVD?|10"
	# "64291453245+12657634|0123456789|+-*/%()|1||64304110879"
	# "1+--11|0123456789|+-*/%()|1|'+' multiple '-'|12"
	# "--1+--11|0123456789|+-*/%()|1|'+' multiple '-'|12"
	# "1b122cc|abcdefghij|1234567|1|'+' multiple '-'|cd"
	# "+1+01|01|+-*/%()|1|Binary base|10"
	# "1b1ab|ab|1234567|1|Binary base|ba"

		## soustraction
	# "44-2|0123456789|+-*/%()|1|With spaces|42"
	# "1--59|0123456789|+-*/%()|1|minus neg|60"
	# "+1--11|01234|+-*/%()|1|plus minus neg|12"
	# "1b22cc|abcde|1234567|1|plus minus neg|cd"

		## multiplication
	# "6*2|0123456789|+-*/%()|1|Simple|12"						# ok
	# "-6*2|0123456789|+-*/%()|1|Minus|-12"						# negatif * positif
	# "6.2*2.4|0123456789|+-*/%()|1|Comas|14.88"					# avec virgules
	# "--00+6*2|0123456789|+-*/%()|1|Minus minus|12"				# 
	# "}}aa{g[c|abcdefghij|{}[]&$%|1|Minus minus|bc"				# 
	# "-6.2*2.4|0123456789|+-*/%()|1|Minus comas|-14.88"			# 

		## multi operateurs
	# "40+4.5-2.2-0.3|0123456789|+-*/%()|1|Comas|42"				# spaces between some parts
	# "17++-++-2.3*4|0123456789|+-*/%()|1|multiple + -|26.2"		# ok with multiple +
	# "{+aabaabx.tc4|&{xt4#6+89|abcdefg|1|multiple + -|x6.x"		# ok with multiple +
	# "42+6*2|0123456789|+-*/%()|1||54" #ok
	# "ec{g[c|abcdefghij|{}[]&$%|1||fe" # ok
	# "1+--00+6*2|0123456789|+-*/%()|1||13" # ok
	# "b{}}aa{g[c|abcdefghij|{}[]&$%|1||bd" # ok

		## tricky tests
	# "40 + 4-2|0123456789|+-*/%()|1|With spaces|42"				# spaces between some parts
	# "42|0123456789|+-*/%()|1|single number|42"					# ok simple number
	# "4.2|0123456789|+-*/%()|1|single number with .|4.2"					# ok simple number with coma
	# "(((42)))|0123456789|+-*/%()|1|1 number between '()'|42"	# ok simple number entre ()
	# "42|0123456789|+-*/%()@|1|Too many operators|42"			# not fatal too many operators

			### KO EXPR
	# "{+ax.tc4d#|&{xt4#6+89|abcdef|0|not enough operators"		# pas assez d'operateurs
	# "}}aa{g[bb|abcdefghij@|{}[]&$%@|0|Too many && Duplicated"	# Too many && duplicated operatord
	# "b{c|abcdefghig|{}[]&$%|0|Duplicated 'g' base"				# 'g' dans base
	# "b{c|abcdefghij|{}[]{$%|0|Duplicated '{' operator"			# '{' dans operateurs
	# "b{c|abcdefghij]|{}[]&$%|0|Duplicated ']' base/operator"	# ']' dans base && operateurs
	# "1+2.2.2|0123456789|+-*/%()|0|too many coma"				# trop de virgule
	# "1+2.2.2.2|0123456789|+-*/%()|0|too many coma"				# trop de virgule
	# "{+ax.th4d#|&{xt4#6+89|abcdefg|0|unauthorised 'h'"			# caractere 'h' invalide
	# "c{+ax.tc4d#|&{xt4#6+89|abcdefg|0|invalid first char"		# 1er caractere 'c' invalide
	# "{+ax.tc4d#f|&{xt4#6+89|abcdefg|0|invalid last char"		# der caractere 'f' invalide
	# "{+ax.tgc4d#|&{xt4#6+89|abcdefg|0| '(' missing"				# ( manquante
	# "{+affx.tgc4d#|&{xt4#6+89|abcdefg|0| ')' missing"			# ) manquante

)

for index in ${!questions[*]}
do
	sleep 0.5
	EXPR="$(echo ${questions[$index]} | cut -d"|" -f 1)"
	BASE="$(echo ${questions[$index]} | cut -d"|" -f 2)"
	OPER="$(echo ${questions[$index]} | cut -d"|" -f 3)"
	VALI="$(echo ${questions[$index]} | cut -d"|" -f 4)"
	COMM="$(echo ${questions[$index]} | cut -d"|" -f 5)"
	EXPE="$(echo ${questions[$index]} | cut -d"|" -f 6)"

	if [ $VALI = "1" ]; then
		VALI=true
		COLOR=92
	elif [ $VALI = "0" ]; then
		VALI=false
		COLOR=91
	fi

	echo -e "\e[47;30m\u2193 Calcul: \"$EXPR\" \"$BASE\" \"$OPER\"\e[m:\e[${COLOR}m $VALI\e[m ($COMM)"

	php eval_expr.php "$EXPR" "$BASE" "$OPER" $1

	output=$(php eval_expr.php "$EXPR" "$BASE" "$OPER" | tail -1)

	if [ $VALI = true ]; then
		if [ $output = $EXPE ]; then
			if [ -z ${1} ]; then
				printf "  "
			fi
				echo -e "\e[42mPASSED\e[m"
		else
			if [ -z ${1} ]; then
				printf "  "
			fi
			printf "\e[5;41mFAILED\e[m"
			if [ -z ${1} ]; then
				printf " "
			else
				printf "\n$> "
			fi
			echo -e "Expected \e[4m$EXPE\e[m"
		fi
	fi
	printf "\n"
done

### cas a tester a la main:
# (void)
# "1+1"
# "b+c" "abcdefghij"
# "1+1" "0123456789" "+-*/%()" "theo"



# exitcode=$?
# echo $exitcode
