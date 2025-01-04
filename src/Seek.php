<?php
namespace plibv4\streams;
enum Seek: int {
	case SET = SEEK_SET; 
	case CUR = SEEK_CUR;
}