#!/bin/bash
svn add app/ database/ resources/ routes/ composer.json config/ public/ --force
svn commit app/ database/ resources/ routes/ composer.json config/ public/ -m ""
