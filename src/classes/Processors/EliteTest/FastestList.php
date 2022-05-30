<?php

namespace Processors\EliteTest;

use Consts\ErrorCode;
//use Games\Accessors\EliteTestAccessor;
use Holders\ResultData;
/**
 * Description of FastestList
 *
 * @author Lian Zhi Wei <zhiwei.lian@7senses.com>
 */
class FastestList extends Leaderboard{
    
    public function Process(): ResultData {
        
//        $accessor = new EliteTestAccessor();
//        $rows = $accessor->rowsFastest($this->offset, $this->length);
//        
//        $result = new ResultData(ErrorCode::Success);
//        $result->list = [];
//        
//        $ranking = $this->offset + 1;
//        foreach($rows as $row){
//            
//            $result->list[] = [
//                'ranking' => $ranking,
//                'account' => $row->Username,
//                'duration' => $row->DurationStr,
//            ];
//            
//            ++$ranking;
//        }
        
        $result = new ResultData(ErrorCode::Success);
        $result->list = [
            ['ranking' => 1, 'account' => 'peta0341', 'duration' => '00:01:15.810'],
            ['ranking' => 2, 'account' => 'peta0150', 'duration' => '00:01:16.057'],
            ['ranking' => 3, 'account' => 'peta0461', 'duration' => '00:01:16.197'],
            ['ranking' => 4, 'account' => 'peta0151', 'duration' => '00:01:16.438'],
            ['ranking' => 5, 'account' => 'peta0149', 'duration' => '00:01:16.872'],
            ['ranking' => 6, 'account' => 'peta0336', 'duration' => '00:01:16.925'],
            ['ranking' => 7, 'account' => 'peta0474', 'duration' => '00:01:17.295'],
            ['ranking' => 8, 'account' => 'peta0473', 'duration' => '00:01:17.562'],
            ['ranking' => 9, 'account' => 'peta0314', 'duration' => '00:01:17.716'],
            ['ranking' => 10, 'account' => 'peta0468', 'duration' => '00:01:17.796'],
            ['ranking' => 11, 'account' => 'peta0349', 'duration' => '00:01:18.006'],
            ['ranking' => 12, 'account' => 'peta0472', 'duration' => '00:01:18.128'],
            ['ranking' => 13, 'account' => 'peta0471', 'duration' => '00:01:18.229'],
            ['ranking' => 14, 'account' => 'peta0460', 'duration' => '00:01:18.328'],
            ['ranking' => 15, 'account' => 'peta0459', 'duration' => '00:01:19.039'],
            ['ranking' => 16, 'account' => 'peta0334', 'duration' => '00:01:19.161'],
            ['ranking' => 17, 'account' => 'peta0202', 'duration' => '00:01:19.225'],
            ['ranking' => 18, 'account' => 'peta0462', 'duration' => '00:01:19.666'],
            ['ranking' => 19, 'account' => 'peta0438', 'duration' => '00:01:19.833'],
            ['ranking' => 20, 'account' => 'peta0370', 'duration' => '00:01:20.083'],
            ['ranking' => 21, 'account' => 'peta0104', 'duration' => '00:01:20.106'],
            ['ranking' => 22, 'account' => 'peta0105', 'duration' => '00:01:20.149'],
            ['ranking' => 23, 'account' => 'peta0058', 'duration' => '00:01:20.194'],
            ['ranking' => 24, 'account' => 'peta0373', 'duration' => '00:01:20.269'],
            ['ranking' => 25, 'account' => 'peta0113', 'duration' => '00:01:20.308'],
            ['ranking' => 26, 'account' => 'peta0342', 'duration' => '00:01:20.320'],
            ['ranking' => 27, 'account' => 'peta0355', 'duration' => '00:01:20.329'],
            ['ranking' => 28, 'account' => 'peta0677', 'duration' => '00:01:20.337'],
            ['ranking' => 29, 'account' => 'peta0131', 'duration' => '00:01:20.428'],
            ['ranking' => 30, 'account' => 'peta0475', 'duration' => '00:01:20.529'],
            ['ranking' => 31, 'account' => 'peta0469', 'duration' => '00:01:20.602'],
            ['ranking' => 32, 'account' => 'peta0124', 'duration' => '00:01:20.772'],
            ['ranking' => 33, 'account' => 'peta0328', 'duration' => '00:01:20.797'],
            ['ranking' => 34, 'account' => 'peta0120', 'duration' => '00:01:20.893'],
            ['ranking' => 35, 'account' => 'peta0442', 'duration' => '00:01:21.083'],
            ['ranking' => 36, 'account' => 'peta0127', 'duration' => '00:01:21.116'],
            ['ranking' => 37, 'account' => 'peta0133', 'duration' => '00:01:21.238'],
            ['ranking' => 38, 'account' => 'peta0023', 'duration' => '00:01:21.528'],
            ['ranking' => 39, 'account' => 'peta0313', 'duration' => '00:01:21.933'],
            ['ranking' => 40, 'account' => 'peta0164', 'duration' => '00:01:21.941'],
            ['ranking' => 41, 'account' => 'peta0332', 'duration' => '00:01:21.983'],
            ['ranking' => 42, 'account' => 'peta0397', 'duration' => '00:01:22.049'],
            ['ranking' => 43, 'account' => 'peta0569', 'duration' => '00:01:22.183'],
            ['ranking' => 44, 'account' => 'peta0121', 'duration' => '00:01:22.183'],
            ['ranking' => 45, 'account' => 'peta0597', 'duration' => '00:01:22.214'],
            ['ranking' => 46, 'account' => 'peta0155', 'duration' => '00:01:22.260'],
            ['ranking' => 47, 'account' => 'peta0553', 'duration' => '00:01:22.404'],
            ['ranking' => 48, 'account' => 'peta0159', 'duration' => '00:01:22.560'],
            ['ranking' => 49, 'account' => 'peta0419', 'duration' => '00:01:22.745'],
            ['ranking' => 50, 'account' => 'peta0661', 'duration' => '00:01:22.799'],
            ['ranking' => 51, 'account' => 'peta0396', 'duration' => '00:01:23.102'],
            ['ranking' => 52, 'account' => 'peta0667', 'duration' => '00:01:23.166'],
            ['ranking' => 53, 'account' => 'peta0380', 'duration' => '00:01:23.179'],
            ['ranking' => 54, 'account' => 'peta0639', 'duration' => '00:01:23.301'],
            ['ranking' => 55, 'account' => 'peta0657', 'duration' => '00:01:23.337'],
            ['ranking' => 56, 'account' => 'peta0516', 'duration' => '00:01:23.533'],
            ['ranking' => 57, 'account' => 'peta0391', 'duration' => '00:01:23.724'],
            ['ranking' => 58, 'account' => 'peta0144', 'duration' => '00:01:23.839'],
            ['ranking' => 59, 'account' => 'peta0561', 'duration' => '00:01:23.849'],
            ['ranking' => 60, 'account' => 'peta0112', 'duration' => '00:01:23.876'],
            ['ranking' => 61, 'account' => 'peta0614', 'duration' => '00:01:23.895'],
            ['ranking' => 62, 'account' => 'peta0427', 'duration' => '00:01:23.957'],
            ['ranking' => 63, 'account' => 'peta0205', 'duration' => '00:01:23.985'],
            ['ranking' => 64, 'account' => 'peta0500', 'duration' => '00:01:23.992'],
            ['ranking' => 65, 'account' => 'peta0412', 'duration' => '00:01:24.075'],
            ['ranking' => 66, 'account' => 'peta0157', 'duration' => '00:01:24.109'],
            ['ranking' => 67, 'account' => 'peta0564', 'duration' => '00:01:24.214'],
            ['ranking' => 68, 'account' => 'peta0410', 'duration' => '00:01:24.309'],
            ['ranking' => 69, 'account' => 'peta0306', 'duration' => '00:01:24.321'],
            ['ranking' => 70, 'account' => 'peta0378', 'duration' => '00:01:24.461'],
            ['ranking' => 71, 'account' => 'peta0106', 'duration' => '00:01:24.792'],
            ['ranking' => 72, 'account' => 'peta0409', 'duration' => '00:01:24.816'],
            ['ranking' => 73, 'account' => 'peta0114', 'duration' => '00:01:24.862'],
            ['ranking' => 74, 'account' => 'peta0546', 'duration' => '00:01:24.877'],
            ['ranking' => 75, 'account' => 'peta0441', 'duration' => '00:01:24.883'],
            ['ranking' => 76, 'account' => 'peta0555', 'duration' => '00:01:24.903'],
            ['ranking' => 77, 'account' => 'peta0645', 'duration' => '00:01:25.053'],
            ['ranking' => 78, 'account' => 'peta0208', 'duration' => '00:01:25.163'],
            ['ranking' => 79, 'account' => 'peta0156', 'duration' => '00:01:25.293'],
            ['ranking' => 80, 'account' => 'peta0215', 'duration' => '00:01:25.300'],
            ['ranking' => 81, 'account' => 'peta0017', 'duration' => '00:01:25.416'],
            ['ranking' => 82, 'account' => 'peta0565', 'duration' => '00:01:25.593'],
            ['ranking' => 83, 'account' => 'peta0449', 'duration' => '00:01:25.608'],
            ['ranking' => 84, 'account' => 'peta0350', 'duration' => '00:01:25.900'],
            ['ranking' => 85, 'account' => 'peta0439', 'duration' => '00:01:26.100'],
            ['ranking' => 86, 'account' => 'peta0388', 'duration' => '00:01:26.130'],
            ['ranking' => 87, 'account' => 'peta0636', 'duration' => '00:01:26.177'],
            ['ranking' => 88, 'account' => 'peta0501', 'duration' => '00:01:26.183'],
            ['ranking' => 89, 'account' => 'peta0675', 'duration' => '00:01:26.207'],
            ['ranking' => 90, 'account' => 'peta0488', 'duration' => '00:01:26.217'],
            ['ranking' => 91, 'account' => 'peta0129', 'duration' => '00:01:26.247'],
            ['ranking' => 92, 'account' => 'peta0108', 'duration' => '00:01:26.250'],
            ['ranking' => 93, 'account' => 'peta0617', 'duration' => '00:01:26.350'],
            ['ranking' => 94, 'account' => 'peta0458', 'duration' => '00:01:26.385'],
            ['ranking' => 95, 'account' => 'peta0020', 'duration' => '00:01:26.442'],
            ['ranking' => 96, 'account' => 'peta0533', 'duration' => '00:01:26.478'],
            ['ranking' => 97, 'account' => 'peta0203', 'duration' => '00:01:26.513'],
            ['ranking' => 98, 'account' => 'peta0265', 'duration' => '00:01:26.577'],
            ['ranking' => 99, 'account' => 'peta0522', 'duration' => '00:01:26.595'],
            ['ranking' => 100, 'account' => 'peta0545', 'duration' => '00:01:26.648'],
            ['ranking' => 101, 'account' => 'peta0596', 'duration' => '00:01:26.659'],
            ['ranking' => 102, 'account' => 'peta0316', 'duration' => '00:01:26.725'],
            ['ranking' => 103, 'account' => 'peta0674', 'duration' => '00:01:26.826'],
            ['ranking' => 104, 'account' => 'peta0118', 'duration' => '00:01:26.926'],
            ['ranking' => 105, 'account' => 'peta0123', 'duration' => '00:01:27.220'],
            ['ranking' => 106, 'account' => 'peta0170', 'duration' => '00:01:27.431'],
            ['ranking' => 107, 'account' => 'peta0401', 'duration' => '00:01:27.507'],
            ['ranking' => 108, 'account' => 'peta0022', 'duration' => '00:01:27.700'],
            ['ranking' => 109, 'account' => 'peta0589', 'duration' => '00:01:27.784'],
            ['ranking' => 110, 'account' => 'peta0119', 'duration' => '00:01:27.838'],
            ['ranking' => 111, 'account' => 'peta0111', 'duration' => '00:01:27.970'],
            ['ranking' => 112, 'account' => 'peta0485', 'duration' => '00:01:28.035'],
            ['ranking' => 113, 'account' => 'peta0361', 'duration' => '00:01:28.040'],
            ['ranking' => 114, 'account' => 'peta0201', 'duration' => '00:01:28.055'],
            ['ranking' => 115, 'account' => 'peta0642', 'duration' => '00:01:28.064'],
            ['ranking' => 116, 'account' => 'peta0103', 'duration' => '00:01:28.066'],
            ['ranking' => 117, 'account' => 'peta0021', 'duration' => '00:01:28.159'],
            ['ranking' => 118, 'account' => 'peta0204', 'duration' => '00:01:28.468'],
            ['ranking' => 119, 'account' => 'peta0602', 'duration' => '00:01:28.504'],
            ['ranking' => 120, 'account' => 'peta0061', 'duration' => '00:01:28.504'],
            ['ranking' => 121, 'account' => 'peta0649', 'duration' => '00:01:28.538'],
            ['ranking' => 122, 'account' => 'peta0669', 'duration' => '00:01:28.827'],
            ['ranking' => 123, 'account' => 'peta0142', 'duration' => '00:01:28.828'],
            ['ranking' => 124, 'account' => 'peta0395', 'duration' => '00:01:28.897'],
            ['ranking' => 125, 'account' => 'peta0627', 'duration' => '00:01:28.972'],
            ['ranking' => 126, 'account' => 'peta0656', 'duration' => '00:01:28.996'],
            ['ranking' => 127, 'account' => 'peta0186', 'duration' => '00:01:29.065'],
            ['ranking' => 128, 'account' => 'peta0160', 'duration' => '00:01:29.099'],
            ['ranking' => 129, 'account' => 'peta0683', 'duration' => '00:01:29.116'],
            ['ranking' => 130, 'account' => 'peta0256', 'duration' => '00:01:29.193'],
            ['ranking' => 131, 'account' => 'peta0432', 'duration' => '00:01:29.301'],
            ['ranking' => 132, 'account' => 'peta0312', 'duration' => '00:01:29.333'],
            ['ranking' => 133, 'account' => 'peta0399', 'duration' => '00:01:29.406'],
            ['ranking' => 134, 'account' => 'peta0024', 'duration' => '00:01:29.464'],
            ['ranking' => 135, 'account' => 'peta0570', 'duration' => '00:01:29.506'],
            ['ranking' => 136, 'account' => 'peta0408', 'duration' => '00:01:29.535'],
            ['ranking' => 137, 'account' => 'peta0641', 'duration' => '00:01:29.566'],
            ['ranking' => 138, 'account' => 'peta0140', 'duration' => '00:01:29.571'],
            ['ranking' => 139, 'account' => 'peta0433', 'duration' => '00:01:29.634'],
            ['ranking' => 140, 'account' => 'peta0379', 'duration' => '00:01:29.799'],
            ['ranking' => 141, 'account' => 'peta0191', 'duration' => '00:01:29.867'],
            ['ranking' => 142, 'account' => 'peta0158', 'duration' => '00:01:29.876'],
            ['ranking' => 143, 'account' => 'peta0638', 'duration' => '00:01:29.898'],
            ['ranking' => 144, 'account' => 'peta0507', 'duration' => '00:01:30.312'],
            ['ranking' => 145, 'account' => 'peta0434', 'duration' => '00:01:30.359'],
            ['ranking' => 146, 'account' => 'peta0701', 'duration' => '00:01:30.379'],
            ['ranking' => 147, 'account' => 'peta0240', 'duration' => '00:01:30.693'],
            ['ranking' => 148, 'account' => 'peta0424', 'duration' => '00:01:30.815'],
            ['ranking' => 149, 'account' => 'peta0310', 'duration' => '00:01:31.012'],
            ['ranking' => 150, 'account' => 'peta0622', 'duration' => '00:01:31.324'],
            ['ranking' => 151, 'account' => 'peta0375', 'duration' => '00:01:31.353'],
            ['ranking' => 152, 'account' => 'peta0550', 'duration' => '00:01:31.359'],
            ['ranking' => 153, 'account' => 'peta0670', 'duration' => '00:01:31.699'],
            ['ranking' => 154, 'account' => 'peta0411', 'duration' => '00:01:31.750'],
            ['ranking' => 155, 'account' => 'peta0006', 'duration' => '00:01:31.806'],
            ['ranking' => 156, 'account' => 'peta0369', 'duration' => '00:01:31.877'],
            ['ranking' => 157, 'account' => 'peta0421', 'duration' => '00:01:31.968'],
            ['ranking' => 158, 'account' => 'peta0549', 'duration' => '00:01:32.277'],
            ['ranking' => 159, 'account' => 'peta0577', 'duration' => '00:01:32.350'],
            ['ranking' => 160, 'account' => 'peta0206', 'duration' => '00:01:32.411'],
            ['ranking' => 161, 'account' => 'peta0456', 'duration' => '00:01:32.666'],
            ['ranking' => 162, 'account' => 'peta0134', 'duration' => '00:01:32.858'],
            ['ranking' => 163, 'account' => 'peta0188', 'duration' => '00:01:32.982'],
            ['ranking' => 164, 'account' => 'peta0317', 'duration' => '00:01:33.183'],
            ['ranking' => 165, 'account' => 'peta0457', 'duration' => '00:01:33.203'],
            ['ranking' => 166, 'account' => 'peta0100', 'duration' => '00:01:33.338'],
            ['ranking' => 167, 'account' => 'peta0394', 'duration' => '00:01:33.423'],
            ['ranking' => 168, 'account' => 'peta0650', 'duration' => '00:01:33.435'],
            ['ranking' => 169, 'account' => 'peta0169', 'duration' => '00:01:33.444'],
            ['ranking' => 170, 'account' => 'peta0644', 'duration' => '00:01:33.495'],
            ['ranking' => 171, 'account' => 'peta0643', 'duration' => '00:01:33.668'],
            ['ranking' => 172, 'account' => 'peta0668', 'duration' => '00:01:33.910'],
            ['ranking' => 173, 'account' => 'peta0406', 'duration' => '00:01:34.029'],
            ['ranking' => 174, 'account' => 'peta0673', 'duration' => '00:01:34.067'],
            ['ranking' => 175, 'account' => 'peta0019', 'duration' => '00:01:34.206'],
            ['ranking' => 176, 'account' => 'peta0305', 'duration' => '00:01:34.236'],
            ['ranking' => 177, 'account' => 'peta0663', 'duration' => '00:01:34.325'],
            ['ranking' => 178, 'account' => 'peta0685', 'duration' => '00:01:34.328'],
            ['ranking' => 179, 'account' => 'peta0453', 'duration' => '00:01:34.379'],
            ['ranking' => 180, 'account' => 'peta0325', 'duration' => '00:01:34.520'],
            ['ranking' => 181, 'account' => 'peta0353', 'duration' => '00:01:34.632'],
            ['ranking' => 182, 'account' => 'peta0163', 'duration' => '00:01:34.699'],
            ['ranking' => 183, 'account' => 'peta0447', 'duration' => '00:01:34.764'],
            ['ranking' => 184, 'account' => 'peta0301', 'duration' => '00:01:34.865'],
            ['ranking' => 185, 'account' => 'peta0197', 'duration' => '00:01:34.912'],
            ['ranking' => 186, 'account' => 'peta0008', 'duration' => '00:01:35.094'],
            ['ranking' => 187, 'account' => 'peta0672', 'duration' => '00:01:35.198'],
            ['ranking' => 188, 'account' => 'peta0637', 'duration' => '00:01:35.339'],
            ['ranking' => 189, 'account' => 'peta0723', 'duration' => '00:01:35.388'],
            ['ranking' => 190, 'account' => 'peta0448', 'duration' => '00:01:35.444'],
            ['ranking' => 191, 'account' => 'peta0652', 'duration' => '00:01:35.500'],
            ['ranking' => 192, 'account' => 'peta0446', 'duration' => '00:01:35.512'],
            ['ranking' => 193, 'account' => 'peta0126', 'duration' => '00:01:35.661'],
            ['ranking' => 194, 'account' => 'peta0623', 'duration' => '00:01:35.682'],
            ['ranking' => 195, 'account' => 'peta0686', 'duration' => '00:01:35.694'],
            ['ranking' => 196, 'account' => 'peta0190', 'duration' => '00:01:35.829'],
            ['ranking' => 197, 'account' => 'peta0598', 'duration' => '00:01:35.849'],
            ['ranking' => 198, 'account' => 'peta0523', 'duration' => '00:01:35.986'],
            ['ranking' => 199, 'account' => 'peta0060', 'duration' => '00:01:36.003'],
            ['ranking' => 200, 'account' => 'peta0364', 'duration' => '00:01:36.170'],
            ['ranking' => 201, 'account' => 'peta0211', 'duration' => '00:01:36.261'],
            ['ranking' => 202, 'account' => 'peta0146', 'duration' => '00:01:36.383'],
            ['ranking' => 203, 'account' => 'peta0632', 'duration' => '00:01:36.428'],
            ['ranking' => 204, 'account' => 'peta0648', 'duration' => '00:01:36.717'],
            ['ranking' => 205, 'account' => 'peta0269', 'duration' => '00:01:36.824'],
            ['ranking' => 206, 'account' => 'peta0646', 'duration' => '00:01:36.912'],
            ['ranking' => 207, 'account' => 'peta0440', 'duration' => '00:01:36.916'],
            ['ranking' => 208, 'account' => 'peta0213', 'duration' => '00:01:37.149'],
            ['ranking' => 209, 'account' => 'peta0055', 'duration' => '00:01:37.200'],
            ['ranking' => 210, 'account' => 'peta0680', 'duration' => '00:01:37.243'],
            ['ranking' => 211, 'account' => 'peta0154', 'duration' => '00:01:37.255'],
            ['ranking' => 212, 'account' => 'peta0366', 'duration' => '00:01:37.333'],
            ['ranking' => 213, 'account' => 'peta0725', 'duration' => '00:01:37.349'],
            ['ranking' => 214, 'account' => 'peta0572', 'duration' => '00:01:37.360'],
            ['ranking' => 215, 'account' => 'peta0557', 'duration' => '00:01:37.483'],
            ['ranking' => 216, 'account' => 'peta0209', 'duration' => '00:01:37.513'],
            ['ranking' => 217, 'account' => 'peta0720', 'duration' => '00:01:37.644'],
            ['ranking' => 218, 'account' => 'peta0496', 'duration' => '00:01:37.958'],
            ['ranking' => 219, 'account' => 'peta0429', 'duration' => '00:01:38.016'],
            ['ranking' => 220, 'account' => 'peta0464', 'duration' => '00:01:38.020'],
            ['ranking' => 221, 'account' => 'peta0309', 'duration' => '00:01:38.049'],
            ['ranking' => 222, 'account' => 'peta0066', 'duration' => '00:01:38.061'],
            ['ranking' => 223, 'account' => 'peta0490', 'duration' => '00:01:38.175'],
            ['ranking' => 224, 'account' => 'peta0618', 'duration' => '00:01:38.358'],
            ['ranking' => 225, 'account' => 'peta0403', 'duration' => '00:01:38.417'],
            ['ranking' => 226, 'account' => 'peta0628', 'duration' => '00:01:38.454'],
            ['ranking' => 227, 'account' => 'peta0634', 'duration' => '00:01:38.889'],
            ['ranking' => 228, 'account' => 'peta0207', 'duration' => '00:01:39.033'],
            ['ranking' => 229, 'account' => 'peta0099', 'duration' => '00:01:39.068'],
            ['ranking' => 230, 'account' => 'peta0532', 'duration' => '00:01:39.180'],
            ['ranking' => 231, 'account' => 'peta0610', 'duration' => '00:01:39.366'],
            ['ranking' => 232, 'account' => 'peta0465', 'duration' => '00:01:39.415'],
            ['ranking' => 233, 'account' => 'peta0575', 'duration' => '00:01:39.531'],
            ['ranking' => 234, 'account' => 'peta0321', 'duration' => '00:01:39.607'],
            ['ranking' => 235, 'account' => 'peta0544', 'duration' => '00:01:39.619'],
            ['ranking' => 236, 'account' => 'peta0326', 'duration' => '00:01:39.633'],
            ['ranking' => 237, 'account' => 'peta0562', 'duration' => '00:01:39.691'],
            ['ranking' => 238, 'account' => 'peta0540', 'duration' => '00:01:39.705'],
            ['ranking' => 239, 'account' => 'peta0200', 'duration' => '00:01:39.760'],
            ['ranking' => 240, 'account' => 'peta0704', 'duration' => '00:01:39.913'],
            ['ranking' => 241, 'account' => 'peta0242', 'duration' => '00:01:39.988'],
            ['ranking' => 242, 'account' => 'peta0005', 'duration' => '00:01:40.004'],
            ['ranking' => 243, 'account' => 'peta0514', 'duration' => '00:01:40.035'],
            ['ranking' => 244, 'account' => 'peta0531', 'duration' => '00:01:40.116'],
            ['ranking' => 245, 'account' => 'peta0476', 'duration' => '00:01:40.198'],
            ['ranking' => 246, 'account' => 'peta0719', 'duration' => '00:01:40.200'],
            ['ranking' => 247, 'account' => 'peta0495', 'duration' => '00:01:40.300'],
            ['ranking' => 248, 'account' => 'peta0647', 'duration' => '00:01:40.343'],
            ['ranking' => 249, 'account' => 'peta0212', 'duration' => '00:01:40.407'],
            ['ranking' => 250, 'account' => 'peta0483', 'duration' => '00:01:40.461']
        ];
        
        return $result;
    }
}
