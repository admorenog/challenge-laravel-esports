#!/usr/bin/env bash

artisan(){
        local phpOpts
        local artisanOpts
        local challengeOpts
        phpOpts="artisan"
        artisanOpts="best_player optimize"
        challengeOpts="rankings_good rankings_empty rankings_lol_fixed rankings_valorant_fields_fixed rankings_valorant_fields_rows_fixed rankings_kills_death_fail"
        case $COMP_CWORD in
                1)
                        COMPREPLY=( $(compgen -W "${phpOpts}" -- "${COMP_WORDS[COMP_CWORD]}") )
                        ;;
                2)
                        COMPREPLY=( $(compgen -W "${artisanOpts}" -- "${COMP_WORDS[COMP_CWORD]}") )
                        ;;
                3)
                        COMPREPLY=( $(compgen -W "${challengeOpts}" -- "${COMP_WORDS[COMP_CWORD]}") )
                        ;;
        esac
        return 0
}

complete -F artisan php
