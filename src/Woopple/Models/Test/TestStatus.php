<?php

namespace Woopple\Models\Test;

enum TestStatus: string
{
    case NEW = 'new';
    case FINISHED = 'finished';
}