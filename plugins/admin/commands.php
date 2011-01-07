<?php
/*
class HelpAdminCommand extends AdminCommand {
    static function CmdHelp($cmd) {
        if ($cmd[0] == self::$firstchar) {
            $cmd = substr($cmd, 1);
        } 
        if ( empty($cmd) ) { //show list of all commands
            $list = array_keys(self::$commands);
            sort($list);
            $commands = self::$firstchar.implode(', '.self::$firstchar, $list );
               
            SteelBot::Msg( self::_('cmdhelp_1', $commands) );
               
        } elseif (array_key_exists($cmd, self::$commands)) { // show help
            SteelBot::Msg(self::$commands[$cmd]['helpstr']);
            
        } else {    //command not found
            SteelBot::Msg(self::_('cmdhelp_2', self::$firstchar, $cmd) ); 
            
        }
    }
}
class CmdAdminCommand extends AdminCommand {
    static function CmdCmd($p1) {    
    if (empty($p1)) {
        SteelBot::Msg(self::_('cmdcmd_1')." access|alias|remove|rename ");
        return;
    }

    list($subcommand, $p1) = explode(' ', $p1, 2);


    switch ($subcommand) {
        case 'access':
            self::AccessCmd($p1);
            break;
        case 'rename':
            list($old, $new) = explode(' ', $p1,2);
            self::RenameCmd($old, $new);
            break;
        case 'alias':
            list($old, $new) = explode(' ', $p1,2);
            self::AliasCmd($old, $new);
            break;
        case 'remove':
            self::RemoveAlias($p1);
            
        case 'disable':
            self::DisableCmd($p1, 'disable');    
            break;
            
        case 'enable':
            self::DisableCmd($p1, 'enable');
            break;    

        default: SteelBot::Msg(self::_('cmdcmd_1')." access|rename|alias|remove|disable|enable ");
    }
}

private static function RenameCmd($old_alias, $new_alias) {
    if (empty($new_alias)) {
        SteelBot::Msg( self::_('cmdcmd_1').' old_alias new_alias' );
    } else {
        self::AliasCmd($old_alias, $new_alias);
        unset( SteelBot::$aliases[$old_alias] );
        SteelBot::Msg(self::_('cmdcmd_3', $old_alias, $new_alias) );
    }
}

private static function DisableCmd($alias, $method) {
    if (array_key_exists($alias, SteelBot::$aliases)) { 
        SteelBot::$aliases[$alias]->$method();
        SteelBot::Msg(self::_('cmdcmd_'.($method=='disable'?'5':'6'), $alias));
    } else {
        SteelBot::Msg(self::_('cmdcmd_7', $alias));
    }       
}

private static function AliasCmd($alias, $new_alias) {
    if (empty($new_alias)) {
        SteelBot::Msg( self::_('cmdcmd_1').' alias new_alias' );
    } else {
        SteelBot::AddAlias(
			SteelBot::$aliases[$alias]->plugin->GetName(),
			SteelBot::$aliases[$alias]->GetName(),
			$new_alias);
        SteelBot::Msg(self::_('cmdcmd_2', $new_alias) );
    }
}

private static function RemoveAlias($alias) {
    unset(SteelBot::$aliases[$alias]);
    SteelBot::Msg( self::_('cmdcmd_4', $alias) );
}

private static function AccessCmd($p1) {
    if (empty($p1)) {
        SteelBot::Msg(self::_('cmdcmdaccess_1', self::$firstchar));
        return;
    }
    list($p1, $p2) = explode(' ', $p1,2);
    if (is_null($p2)) {
        if (array_key_exists($p1, SteelBot::$aliases)) {
			$commandName = SteelBot::$aliases[$p1]->GetName();
			$pluginName = SteelBot::$aliases[$p1]->plugin->GetName();
			
            SteelBot::Msg(self::_('cmdcmdaccess_2', $p1, SteelBot::$aliases[$p1]->GetAccess(), $commandName, $pluginName));
        } else {
            SteelBot::Msg(self::_('cmdcmdaccess_3', $p1));
        }

    } elseif ( ((int)$p2 <= 100) && ((int)$p2 > 0) ) {
        if (array_key_exists($p1, SteelBot::$aliases)) {
            try {
                SteelBot::SetCmdAccess(
					SteelBot::$aliases[$p1]->plugin->GetName(),
					SteelBot::$aliases[$p1]->GetName(),
                     $p2
                );
                SteelBot::Msg(self::_('cmdcmdaccess_4', $p1, $p2));
            } catch (BotException $e) {
                SteelBot::Msg("Exception: ".$e->getMessage());
            }

        } else {
            SteelBot::Msg('command not found');
        }
    } else {
        SteelBot::Msg(self::_('cmdcmdaccess_5'));
    }
} 

} */

class EvalAdminCommand extends AdminCommand {
    
    protected $helpstr_full = '.eval - интерпретирует php код',
              $helpstr_short = '.eval - интерпретирует php код';

    public function __construct() {
        parent::__construct('eval');
    }
        
    public function Execute($params, &$msgevent) {
        parent::Execute($params, $msgevent);
        ob_start();
        eval($params);
        $output = ob_get_flush();
        if (empty($output)) {
            $output = "Выполнено.";
        }
        S::bot()->Msg($output);
    }
}
           
class ExitAdminCommand extends AdminCommand {
    protected $helpstr_full = '.exit - завершение работы бота',
              $helpstr_short = '.exit - завершение работы бота';

    public function __construct() {
        parent::__construct('exit');
    }
    
    public function Execute($params, &$msgevent) {
        parent::Execute($params, $msgevent);
        S::logger()->log("Exit requested by ".$msgevent->sender);
        S::bot()->Msg("Завершение работы...");
        S::bot()->DoExit();    
    }
}

/*
class OptAdminCommand extends AdminCommand {
    static function CmdOpt($val) {
    
    list($action, $p1) = explode(' ', $val, 2);
    switch ($action) {
        case 'create': list($p1, $p2) = explode(' ', $p1,2);
                       if (!array_key_exists($p1, SteelBot::$cfg)) {
                           SteelBot::$cfg[$p1] = $p2;
                           SteelBot::Msg( self::_('cmdopt_1'), $p1, $p2);
                           
                       } else {
                           SteelBot::Msg(self::_('cmdopt_2', $p1) );
                       }
                       break;
                       
        case 'reset': $p1 = array_pop( explode(' ', $p1,2));
                       if (array_key_exists($p1, SteelBot::$cfg)) {
                           SteelBot::$database->DeleteOption($p1);
                           SteelBot::Msg(self::_('cmdopt_3', $p1));
                           
                       } else {
                           SteelBot::Msg(self::_('cmdopt_4', $p1));
                       }
                       break;
                       
        case 'set':    list($p1, $p2) = explode(' ', $p1,2);
                       if ($p1 == 'password') SteelBot::$cfg['password'] = '<hidden>';
                       if ($p1 == 'master_accounts') {
                           SteelBot::Msg(self::_('cmdopt_5'));
                           return;
                       }
                       if (array_key_exists($p1, SteelBot::$cfg)) {
                           $oldval = SteelBot::$cfg[$p1];
                           SteelBot::SetOption($p1, $p2);
                           SteelBot::Msg(self::_('cmdopt_6', $p1, $p2, $oldval));
                       }
                       break;
        
        case 'list':   $p1 = array_pop( explode(' ', $p1,2));
                       if (empty($p1)) {
                           $options = implode(' ,', array_keys(SteelBot::$cfg) );
                           Steelbot::Msg(self::_('cmdopt_7', $options));
                           
                       } elseif (array_key_exists($p1, SteelBot::$cfg)) {
                           switch ($p1) {
                               case 'password': $value = '<hidden>';
                                  break;
                               default: $value = SteelBot::$cfg[$p1];
                                  break;
                           }
                           
                           SteelBot::Msg(self::_('cmdopt_8', $p1, $value));
                           
                       } else {
                           SteelBot::Msg(self::_('cmdopt_9', $p1));
                       }
                       break;
                       
        default:               
                        $options = implode(' ,', array_keys(SteelBot::$cfg) );
                        Steelbot::Msg(self::_('cmdopt_10', $options));               
    }  
}}
class PluginAdminCommand extends AdminCommand {
    static function CmdPlugin($param) {
    if (empty($param)) {
        $plugins = implode(", ", array_keys(SteelBot::$plugins) );
        SteelBot::Msg(self::_('cmdplugins_1', $plugins));    
    } else {
        list($cmd, $p1) = explode(' ', $param);
        switch($cmd) {
            case 'load': if ( SteelBot::LoadPluginByName($p1) ) {
                             SteelBot::Msg(self::_('cmdplugins_2', $p1));
                         } else {
                             SteelBot::Msg(self::_('cmdplugins_3'));
                         }
                break;
                         
            case 'enable': if (array_key_exists($p1, SteelBot::$plugins)) {
                               slog::add('admin', "Enabling $p1 ...");
                               $cmds = SteelBot::$plugins[$p1]->GetCommands();
                               foreach ($cmds as $cmd) {
                                   SteelBot::$commands[$cmd]->Enable();
                               }
                               slog::result('OK');
                               SteelBot::Msg(SteelBot::Msg(self::_('cmdplugins_6', $p1)));
                           } elseif (SteelBot::LoadPluginByName($p1)) {
                               slog::add('admin', "Plugin $p1 loaded");
                               $p1 = str_replace('.plugin.php', '', $p1);
                               SteelBot::CheckDependency($p1);
                               SteelBot::Msg(self::_('cmdplugins_7', $p1));
                           } else {
                               slog::add('admin', "Plugin $p1 not found");
                               SteelBot::mSG(self::_('cmdplugins_5', $p1));
                           }
                
                break;
                
            case 'disable':
                           if (array_key_exists($p1, SteelBot::$plugins)) {
                               $cmds = SteelBot::$plugins[$p1]->GetCommands();
                               $i = 0;
                               foreach ($cmds as $cmd) {
                                   $i++;
                                   SteelBot::$commands[$cmd]->Disable();
                               }
                               SteelBot::Msg(SteelBot::Msg(self::_('cmdplugins_8', $p1, $i)));
                           }
                break;
                         
            case 'info': if (array_key_exists($p1, SteelBot::$plugins)) {
                            $info = SteelBot::$plugins[$p1]->GetInfo();

                            $commands = SteelBot::$plugins[$p1]->GetCommands();
                            foreach ($commands  as $k=>$v) {
                                $commands[$k] = $v->GetName();
                            }
                            
                            $dependencies = SteelBot::$plugins[$p1]->GetDependencies('plugin');
                            $depstr = array();
                            
                            
                            
                            foreach ($dependencies as $k=>$v) {
                                if ( ($v['version'] == '99.99') ) {
                                    $depstr[] = $v['dep'];
                                } else {
                                    $depstr[] = $v['dep'].' '.$v['version'];
                                }
                            }
                            $message = self::_( 'cmdplugins_4', 
                                                $p1, 
                                                $info['author'], 
                                                $info['version'],
                                                implode(', ', $commands),
                                                implode(', ', $depstr)
                                                );
                            SteelBot::Msg($message);
                         } else {
                            SteelBot::Msg(self::_('cmdplugins_5'), $p1); 
                         }
                         break;
        }
    }
}}
class ReconnectAdminCommand extends AdminCommand {
    static function CmdReconnect() {
    SteelBot::Disconnect(); 
    SteelBot::Connect();  
}}   
class UseraccessAdminCommand extends AdminCommand {
    static function CmdUserAccess($p1) {
    if (empty($p1)) {
        SteelBot::Msg(self::_('cmduseraccess_1', self::$firstchar));
        return;
    }
    list($p1, $p2) = explode(' ', $p1,2);    
    if ($p2 == null) {
        SteelBot::Msg(self::_('cmduseraccess_2', $p1, SteelBot::GetUserAccess($p1)));
    } else {
        if ( SteelBot::GetUserAccess($p1) >= 100) {
            SteelBot::Msg(self::_('cmduseraccess_3', $p1));
        } else {
            if ( SteelBot::SetUserAccess($p1,$p2) ) {
                SteelBot::Msg(self::_('cmduseraccess_4', $p1, $p2));
            } else {
                SteelBot::Msg(self::_('cmduseraccess_5'));
            }
        }
        
    }
}}  
class TimerAdminCommand extends AdminCommand {
    static function CmdTimer($val) {
    list ($cmd, $param) = explode(' ',$val,2);
    switch ($cmd) {
        case 'list':
            $timer_list = self::_('cmdtimer_1');
            foreach (SteelBot::$timers as $label=>$functions) {
                $wait = $label - time();
                foreach ($functions as $func) {          
                    $timer_list .=  '#'.$func[1].' '.date("d M Y H:i:s",$label).' => '.func2str($func[0]).
                                    " ($wait)\n";
                }
            }
            SteelBot::Msg($timer_list);           
            break;
            
        case 'add': 
            list($time, $func) = explode(' ',$param, 2);
            if (strpos($func, '::') !== false) {
                    $func = explode('::', $func);
            }
            switch ( substr_count(':', $time) ) {
                case 0: 
                    $time = (int)$time;
                    break;
                          
                case 1: 
                    list($min,$sec) = explode(':', $time, 2);
                    $time = ((int)$min *60)+$sec;
                    break;
                    
                case 2: 
                    list($hr, $min, $sec) = explode(':', $time, 3);
                    $time = ((int)$hr*3600) + ((int)$min *60) + $sec;
                    break;
                    
                case 3:    
                    list($days, $hr, $min, $sec) = explode(':', $time, 4);
                    $time = ((int)$days*3600*24) + ((int)$hr*3600) + ((int)$min *60) + $sec;
                    break;

            }
                    
            if ( ($time > 0) && ($time < 31536000)) {
                SteelBot::TimerAdd($time, $func);
                SteelBot::Msg(self::_('cmdtimer_2', func2str($func), $time));
            } else {
                SteelBot::Msg(self::_('cmdtimer_3'));                    
            }
            break;
                    
        case 'del': if ($param[0] == '~') {
                        $param = time()+(int)substr($param, 1);
                        $count = 0;
                        foreach (SteelBot::$timers as $k=>$v) {
                            if ($k <= $param) {
                                $count += count(SteelBot::$timers[$k]);
                                unset(SteelBot::$timers[$k]);
                            }
                        }            
                    } elseif ($param[0] == '^') {
                        $func = substr($param, 1);
                        $count = 0;
                        if (strpos($func, '::') !== false) {
                            $func = explode('::', $func);
                        }
                        foreach (SteelBot::$timers as $k=>$v) {
                            foreach ($v as $kk=>$vv) {
                                if ( $func == $vv ) {
                                    array_splice(SteelBot::$timers[$k],$kk,1);
                                    $count++;
                                }    
                            }                           
                        }                      
                    } elseif ($param[0] == '#') {
                        $timer_id = substr($param, 1);
                        if ( SteelBot::TimerDeleteById($timer_id) ) {
                            $count = 1;
                        } else {
                            $count = 0;
                        }
                    }
                  SteelBot::SyncTimers();  
                  SteelBot::Msg(self::_('cmdtimer_4', $count));  
                  break;
        default: 
            if (count(SteelBot::$timers)) {
                $first_timer = min(array_keys(SteelBot::$timers));
                $wait = $first_timer - time();
                $first_timer = date("d M Y H:i:s", $first_timer);
                 
                $timers_count = 0;
                foreach (SteelBot::$timers as $label) {
                    $timers_count += count($label);
                }
                $msg = self::_('cmdtimer_5', $timers_count, $first_timer, $wait);
            } else {
                $msg = self::_('cmdtimer_6');
            }
            SteelBot::Msg($msg);    
                     
    }
}}
class InfoAdminCommand extends AdminCommand {
    static function CmdInfo() {
    $version = SteelBot::GetVersion();
    $proto = Proto::GetProtoInfo();
    $database = Steelbot::$database->GetDBInfo();
    
    $uptime = floor((time() - self::$starttime) / 60) ;
    
    $msg = self::_('cmdinfo_1', 
                   $version, $proto['name'].' '.$proto['version'], 
                   $database['name'].' '.$database['version'],
                   $uptime);
    
    $msg .= self::_('cmdinfo_2', count(SteelBot::$plugins) );
    
    $plugins_list = array_keys(SteelBot::$plugins);
    sort($plugins_list);
    
    foreach ($plugins_list as $p) {
        $info = SteelBot::$plugins[$p]->GetInfo();
        $msg .= '  '.$info['name'].' '.$info['version']."\n";
    }
    
    SteelBot::Msg($msg);
}} */

return array(
    //'HelpAdminCommand',
    //'CmdAdminCommand',
    'EvalAdminCommand',            
    'ExitAdminCommand',
    /*'OptAdminCommand',
    'PluginAdminCommand',
    'ReconnectAdminCommand',    
    'UseraccessAdminCommand',   
    'TimerAdminCommand',
    'InfoAdminCommand'*/ 
);
