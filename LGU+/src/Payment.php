<?php
/**
 * Created by pg.
 * User: littlefox
 * Date: 2020/05/19
 * Time: 11:47 오전
 * File: Payment.php
 */

namespace Lg;

use \Lg\XPayClient;
use Dotenv\Dotenv;

class Payment
{

    /**
     * @var \Lg\XPayClient
     */
    protected $xpay;
    protected $_d = [];

    protected $CST_PLATFORM;                                                // LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
    protected $CST_MID;                                                     // 상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
    protected $LGD_MID;                                                     // (("test" == $CST_PLATFORM) ? "t" : "") . $CST_MID
    protected $LGD_OID;                                                     // 주문번호(상점정의 유니크한 주문번호를 입력하세요)
    protected $LGD_AMOUNT;                                                  // 결제금액("," 를 제외한 결제금액을 입력하세요)
    protected $LGD_BUYER;                                                   // 구매자명
    protected $LGD_PRODUCTINFO;                                             // 상품명
    protected $LGD_BUYEREMAIL;                                              // 구매자 이메일
    protected $LGD_OSTYPE_CHECK;                                            // 값 P: XPay 실행(PC 결제 모듈): PC용과 모바일용 모듈은 파라미터 및 프로세스가 다르므로 PC용은 PC 웹브라우저에서 실행 필요.
    protected $LGD_CUSTOM_SKIN = "red";                                     // 상점정의 결제창 스킨
    protected $LGD_CUSTOM_USABLEPAY;                                        // 디폴트 결제수단 (해당 필드를 보내지 않으면 결제수단 선택 UI 가 노출됩니다.)
    protected $LGD_WINDOW_VER = "2.5";                                      // 결제창 버젼정보
    protected $LGD_WINDOW_TYPE;                                             // 결제창 호출방식 (수정불가)
    protected $LGD_CUSTOM_SWITCHINGTYPE;                                    // 신용카드 카드사 인증 페이지 연동 방식 (수정불가)
    protected $LGD_CUSTOM_PROCESSTYPE = "TWOTR";                            //수정불가
    protected $LGD_CASNOTEURL = "https://상점URL/cas_noteurl.php";           // 가상계좌(무통장) 결제 연동을 하시는 경우
    protected $LGD_RETURNURL = "https://상점URL/returnurl.php";              // 리턴 URL 설정 반드시 필요
    protected $LGD_TIMESTAMP;
    protected $LGD_HASHDATA;

    /**
     * Payment constructor.
     */
    public function __construct()
    {
        // load .env
        $this->loadEnv();
    }

    /**
     * Load .env
     * $_ENV['CST_MID'] or $_SERVER['CST_MID'] or getenv('CST_MID')
     */
    public function loadEnv()
    {
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->CST_PLATFORM = $_ENV['CST_PLATFORM'];
        $this->CST_MID      = $_ENV['CST_MID'];
        $this->LGD_MID      = (("test" == $this->CST_PLATFORM) ? "t" : "") . $this->CST_MID;
    }

    /**
     * MD5 해쉬암호화
     * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
     * LGD_MID          : 상점아이디
     * LGD_OID          : 주문번호
     * LGD_AMOUNT       : 금액
     * LGD_TIMESTAMP    : 타임스탬프
     * LGD_MERTKEY      : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
     */
    public function init()
    {
        $this->xpay = new XPayClient($_ENV[''], $this->CST_PLATFORM);
        if (! $this->xpay->Init_TX($this->LGD_MID)) {
            echo "LG유플러스에서 제공한 환경파일이 정상적으로 설치 되었는지 확인하시기 바랍니다.<br/>";
            echo "mall.conf에는 Mert Id = Mert Key 가 반드시 등록되어 있어야 합니다.<br/><br/>";
            echo "문의전화 LG유플러스 1544-7772<br/>";
            exit;
        }

        $this->LGD_TIMESTAMP = $this->xpay->GetTimeStamp();
        $this->LGD_HASHDATA  = $this->xpay->GetHashData(
            $this->LGD_MID,
            $this->LGD_OID,
            $this->LGD_AMOUNT,
            $this->LGD_TIMESTAMP0
        );
    }

    /**
     * MD5 해쉬암호
     *
     * @return string
     */
    public function getHash()
    {
        return $this->LGD_HASHDATA;
    }
}
